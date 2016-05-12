<?php

use yii\db\Migration;

class m160316_154734_banners extends Migration
{
    public function up()
    {
        $this->execute("UPDATE `banners_type` SET `category` = 0 WHERE `category` = ''");
        $this->alterColumn('banners_type', 'category', \yii\db\Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0');
        foreach(['2x2', '1x1.1', '1x1.2', '1x1.3', '1x1.4', '1x2'] as $item){
            $model = new \common\models\BannerType([
                'alias'         =>  $item,
                'description'   =>  $item
            ]);

            $model->save(false);
        }
    }

    public function down()
    {
        echo "m160316_154734_banners cannot be reverted.\n";

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
