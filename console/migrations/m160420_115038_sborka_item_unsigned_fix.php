<?php

use yii\db\Migration;

class m160420_115038_sborka_item_unsigned_fix extends Migration
{
    public function up()
    {
        $this->alterColumn(\common\models\SborkaItem::tableName(), 'customerRule', \yii\db\Schema::TYPE_INTEGER.' NOT NULL DEFAULT 0');
        $this->alterColumn(\common\models\SborkaItem::tableName(), 'priceRuleID', \yii\db\Schema::TYPE_INTEGER.' NOT NULL DEFAULT 0');
    }

    public function down()
    {
        $this->alterColumn(\common\models\SborkaItem::tableName(), 'customerRule', \yii\db\Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0');
        $this->alterColumn(\common\models\SborkaItem::tableName(), 'priceRuleID', \yii\db\Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0');

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
