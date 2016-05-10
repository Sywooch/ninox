<?php

use yii\db\Migration;

class m160506_082730_hotfix_cashbox_sale extends Migration
{
    public function up()
    {
        $this->alterColumn('history', 'deliveryInfo', \yii\db\Schema::TYPE_TEXT." DEFAULT NULL");
    }

    public function down()
    {
        echo "m160506_082730_hotfix_cashbox_sale cannot be reverted.\n";

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
