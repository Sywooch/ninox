<?php

use common\models\Good;
use yii\db\Migration;
use yii\db\Schema;

class m160503_151119_item_translations extends Migration
{
    public function up()
    {
        $this->createTable('item_translations',[
            'ID'                    =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL',
            'language'              =>  Schema::TYPE_STRING."(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'ru_RU'",
            'enabled'               =>  Schema::TYPE_SMALLINT.' UNSIGNED NOT NULL DEFAULT 0',
            'name'                  =>  Schema::TYPE_STRING."(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''",
            'link'                  =>  Schema::TYPE_STRING."(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''",
            'description'           =>  Schema::TYPE_TEXT.' CHARACTER SET utf8 COLLATE utf8_general_ci',
        ], 'ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci');

        $this->addPrimaryKey('pk_id_language', 'item_translations', ['ID', 'language']);

        $this->createIndex('ID', 'item_translations', 'ID');
        $this->createIndex('language', 'item_translations', 'language');
        $this->createIndex('link', 'item_translations', 'link');

        $this->alterColumn('goods', 'Description', Schema::TYPE_TEXT.' CHARACTER SET utf8 COLLATE utf8_general_ci');
        $this->alterColumn('goods_uk', 'Description', Schema::TYPE_TEXT.' CHARACTER SET utf8 COLLATE utf8_general_ci');
        $this->alterColumn('goods_be', 'Description', Schema::TYPE_TEXT.' CHARACTER SET utf8 COLLATE utf8_general_ci');

        $items = [];
        $itemRU = Good::find()->count();
        $i = 0;
        $all = 0;
        echo "    > prepare array for batch insert into item_translations for ".$itemRU." items... \r \n";

        foreach(Good::find()->each(100) as $model){
            $model->show_img = !empty($model->show_img) ? $model->show_img : 0;
            $model->Name = !empty($model->Name) ? $model->Name : '';

            $items[] = [
                $model->ID,
                'ru_RU',
                $model->show_img,
                $model->Name,
                $model->link,
                $model->Description
            ];
            $items[] = [
                $model->ID,
                'uk_UA',
                $model->show_img,
                '',
                '',
                ''
            ];
            $items[] = [
                $model->ID,
                'be_BY',
                $model->show_img,
                '',
                '',
                ''
            ];
            echo "    > item ".++$all." from ".$itemRU." items \r \n";
            ++$i;
            if($i == 500){
                $this->batchInsert('item_translations', [
                    'ID',
                    'language',
                    'enabled',
                    'name',
                    'link',
                    'description'],
                    $items
                );
                $i = 0;
                $items = [];
            }
        }

        if($items){
            $this->batchInsert('item_translations', [
                'ID',
                'language',
                'enabled',
                'name',
                'link',
                'description'],
                $items
            );
        }

        $this->execute("UPDATE `goods_uk` AS `g`, `item_translations` AS `t` SET
          `t`.`name` = `g`.`Name`,
          `t`.`link` = `g`.`link`,
          `t`.`description` = `g`.`Description`
          WHERE `t`.`ID` = `g`.`ID` AND `t`.`language` = 'uk_UA'");
        $this->execute("UPDATE `goods_be` AS `g`, `item_translations` AS `t` SET
          `t`.`name` = `g`.`Name`,
          `t`.`link` = `g`.`link`,
          `t`.`description` = `g`.`Description`
          WHERE `t`.`ID` = `g`.`ID` AND `t`.`language` = 'be_BY'");
        $this->alterColumn('goods', 'ID', Schema::TYPE_INTEGER.' UNSIGNED NOT NULL AUTO_INCREMENT FIRST');
        $this->execute("ALTER TABLE `goods` ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_general_ci");
        $this->addForeignKey('fk_item_item_translations', 'goods', 'ID', 'item_translations', 'ID', 'CASCADE', 'RESTRICT');
        $this->execute("CREATE TABLE goods_copy LIKE goods; INSERT goods_copy SELECT * FROM goods;");
        $this->dropColumn('goods', 'Name');
        $this->dropColumn('goods', 'Name2');
        $this->dropColumn('goods', 'link');
        $this->dropColumn('goods', 'show_img');
        $this->dropColumn('goods', 'Description');
    }

    public function down()
    {
        $this->dropForeignKey('fk_item_item_translations', 'goods');
        $this->execute("ALTER TABLE `goods` ENGINE=MyISAM CHARSET=utf8 COLLATE=utf8_general_ci");
        $this->dropTable('item_translations');
        $this->dropTable('goods');
        $this->renameTable('goods_copy', 'goods');

        echo "m160503_151119_item_translations was successfully reverted.\n";
        return true;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
