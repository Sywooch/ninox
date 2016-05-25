<?php

use yii\db\Migration;

class m160520_134800_change_foreign_key extends Migration
{
    public function up()
    {
        $this->dropForeignKey('fk_item_item_translations', \common\models\Good::tableName());
        $this->addForeignKey('fk_item_item_translations', \common\models\GoodTranslation::tableName(), 'ID', \common\models\Good::tableName(), 'ID');
    }

    public function down()
    {
        $this->dropForeignKey('fk_item_item_translations', \common\models\GoodTranslation::tableName());
        $this->addForeignKey('fk_item_item_translations', \common\models\Good::tableName(), 'ID', \common\models\GoodTranslation::tableName(), 'ID');
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
