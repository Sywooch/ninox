<?php

use yii\db\Migration;

class m151229_160520_orderID_in_cashbox_order extends Migration
{
    public function up()
    {
        $this->addColumn('cashboxOrders', 'createdOrder', \yii\db\Schema::TYPE_BIGINT.' UNSIGNED DEFAULT 0');
    }

    public function down()
    {
        $this->dropColumn('cashboxOrders', 'createdOrder');

        return false;
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
