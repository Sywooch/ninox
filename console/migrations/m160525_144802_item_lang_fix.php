<?php

use yii\db\Migration;

class m160525_144802_item_lang_fix extends Migration
{
    public function up()
    {
        \common\models\GoodTranslation::updateAll([
            'language' => 'ru-RU'
        ], ['language' => 'ru_RU']);
    }

    public function down()
    {
        echo "m160525_144802_item_lang_fix cannot be reverted.\n";
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
