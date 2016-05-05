<?php

use yii\db\Migration;

class m160505_075448_fix_customerID_in_cashboxOrders extends Migration
{
    public function up()
    {
        $this->alterColumn('cashboxOrders', 'customerID', \yii\db\Schema::TYPE_BIGINT.' UNSIGNED DEFAULT 0');
    }

    public function down()
    {
        echo "m160505_075448_fix_customerID_in_cashboxOrders cannot be reverted.\n";
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
