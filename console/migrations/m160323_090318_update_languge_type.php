<?php

use common\models\BannerTranslation;
use yii\db\Migration;

class m160323_090318_update_languge_type extends Migration
{
    public function up()
    {
        BannerTranslation::updateAll(['language' => 'ru_RU'], ['language' => 'ru']);
    }

    public function down()
    {
        echo "m160323_090318_update_languge_type cannot be reverted.\n";
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
