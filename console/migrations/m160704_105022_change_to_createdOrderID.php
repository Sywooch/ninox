<?php

use yii\db\Migration;

class m160704_105022_change_to_createdOrderID extends Migration
{
    public function up()
    {
        $this->renameColumn('cashboxOrders', 'createdOrder', 'createdOrderID');
    }

    public function down()
    {
        $this->renameColumn('cashboxOrders', 'createdOrderID', 'createdOrder');
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
