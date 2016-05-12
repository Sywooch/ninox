<?php

use common\models\Category;
use common\models\CategoryBe;
use common\models\CategoryUk;
use yii\db\Migration;
use yii\db\Schema;

class m160427_121508_category_translations extends Migration
{
    public function up()
    {
        $this->createTable('category_translations',[
            'ID'                    =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL',
            'language'              =>  Schema::TYPE_STRING.'(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'\'',
            'enabled'               =>  Schema::TYPE_SMALLINT.' UNSIGNED NOT NULL DEFAULT 0',
            'Name'                  =>  Schema::TYPE_STRING.'(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'\'',
            'link'                  =>  Schema::TYPE_STRING.'(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'\'',
            'title'                 =>  Schema::TYPE_STRING.'(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'\'',
            'titleOrderAscending'   =>  Schema::TYPE_STRING.'(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'\'',
            'titleOrderDescending'  =>  Schema::TYPE_STRING.'(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'\'',
            'titleOrderNew'         =>  Schema::TYPE_STRING.'(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'\'',
            'header'                =>  Schema::TYPE_STRING.'(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'\'',
            'headerOrderAscending'  =>  Schema::TYPE_STRING.'(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'\'',
            'headerOrderDescending' =>  Schema::TYPE_STRING.'(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'\'',
            'headerOrderNew'        =>  Schema::TYPE_STRING.'(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'\'',
            'metaDescription'       =>  Schema::TYPE_TEXT.' CHARACTER SET utf8 COLLATE utf8_general_ci',
            'metaKeywords'          =>  Schema::TYPE_TEXT.' CHARACTER SET utf8 COLLATE utf8_general_ci',
            'categoryDescription'   =>  Schema::TYPE_TEXT.' CHARACTER SET utf8 COLLATE utf8_general_ci',
            'sequence'              =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0'
        ], 'ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci');

        $this->createIndex('ID', 'category_translations', 'ID');
        $this->createIndex('language', 'category_translations', 'language');
        $this->createIndex('link', 'category_translations', 'link');

        $categories = [];
        $categoryRU = Category::find()->count();
        $i = 0;
        echo "    > prepare array for batch insert into category_translations for ".$categoryRU." ru_RU categories... \r \n";

        foreach(Category::find()->each(100) as $model){
            $categories[] = [
                $model->ID,
                'ru_RU',
                $model->enabled,
                $model->Name,
                $model->link,
                $model->title,
                $model->titleasc,
                $model->titledesc,
                $model->titlenew,
                $model->h1,
                $model->h1asc,
                $model->h1desc,
                $model->h1new,
                $model->descr,
                $model->keyword,
                $model->text2,
                $model->listorder
            ];
            echo "    > category ".++$i." from ".$categoryRU." ru_RU categories \r \n";
        }

        $this->batchInsert('category_translations', [
            'ID',
            'language',
            'enabled',
            'Name',
            'link',
            'title',
            'titleOrderAscending',
            'titleOrderDescending',
            'titleOrderNew',
            'header',
            'headerOrderAscending',
            'headerOrderDescending',
            'headerOrderNew',
            'metaDescription',
            'metaKeywords',
            'categoryDescription',
            'sequence'],
            $categories
        );

        $categories = [];
        $categoryUA = CategoryUk::find()->count();
        $i = 0;
        echo "    > prepare array for batch insert into category_translations for ".$categoryUA." uk_UA categories... \r \n";

        foreach(CategoryUk::find()->each(100) as $model){
            $categories[] = [
                $model->ID,
                'uk_UA',
                $model->menu_show,
                $model->Name,
                $model->link,
                $model->title,
                $model->titleasc,
                $model->titledesc,
                $model->titlenew,
                $model->h1,
                $model->h1asc,
                $model->h1desc,
                $model->h1new,
                $model->descr,
                $model->keyword,
                $model->text2,
                $model->listorder
            ];
            echo "    > category ".++$i." from ".$categoryUA." uk_UA categories \r \n";
        }

        $this->batchInsert('category_translations', [
            'ID',
            'language',
            'enabled',
            'Name',
            'link',
            'title',
            'titleOrderAscending',
            'titleOrderDescending',
            'titleOrderNew',
            'header',
            'headerOrderAscending',
            'headerOrderDescending',
            'headerOrderNew',
            'metaDescription',
            'metaKeywords',
            'categoryDescription',
            'sequence'],
            $categories
        );

        $categories = [];
        $categoryBY = CategoryBe::find()->count();
        $i = 0;
        echo "    > prepare array for batch insert into category_translations for ".$categoryBY." be_BY categories... \r \n";

        foreach(CategoryBe::find()->each(100) as $model){
            $categories[] = [
                $model->ID,
                'be_BY',
                $model->menu_show,
                $model->Name,
                $model->link,
                $model->title,
                $model->titleasc,
                $model->titledesc,
                $model->titlenew,
                $model->h1,
                $model->h1asc,
                $model->h1desc,
                $model->h1new,
                $model->descr,
                $model->keyword,
                $model->text2,
                $model->listorder
            ];
            echo "    > category ".++$i." from ".$categoryBY." be_BY categories \r \n";
        }

        $this->batchInsert('category_translations', [
            'ID',
            'language',
            'enabled',
            'Name',
            'link',
            'title',
            'titleOrderAscending',
            'titleOrderDescending',
            'titleOrderNew',
            'header',
            'headerOrderAscending',
            'headerOrderDescending',
            'headerOrderNew',
            'metaDescription',
            'metaKeywords',
            'categoryDescription',
            'sequence'],
            $categories
        );

        $this->alterColumn('goodsgroups', 'ID', Schema::TYPE_INTEGER.' UNSIGNED NOT NULL AUTO_INCREMENT FIRST');
        $this->execute("ALTER TABLE `goodsgroups` ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_general_ci");
        $this->addForeignKey('fk_category_category_translations', 'goodsgroups', 'ID', 'category_translations', 'ID', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('fk_category_category_translations', 'goodsgroups');
        $this->execute("ALTER TABLE `goodsgroups` ENGINE=MyISAM CHARSET=utf8 COLLATE=utf8_general_ci");
        $this->dropTable('category_translations');

        echo "m160427_121508_category_translations was successfully reverted.\n";
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
