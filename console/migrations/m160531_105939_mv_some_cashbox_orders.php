<?php

use yii\db\Migration;

class m160531_105939_mv_some_cashbox_orders extends Migration
{
    public function up()
    {
        $this->execute("UPDATE `history`, `cashboxOrders` SET `cashboxOrders`.`source` = `history`.`orderSource` WHERE `cashboxOrders`.`createdOrder` = `history`.`ID`");
    }

    public function down()
    {
        echo "m160531_105939_mv_some_cashbox_orders cannot be reverted.\n";

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
