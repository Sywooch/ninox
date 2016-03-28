<?php

use yii\db\Migration;

class m160227_123734_update_cashboxmoney extends Migration
{
    public function up()
    {
        $this->execute("UPDATE `cashboxMoney`, `cashboxOrders` SET `cashboxMoney`.`order` = `cashboxOrders`.`id` WHERE `cashboxOrders`.`createdOrder` != '' AND `cashboxMoney`.`order` = `cashboxOrders`.`createdOrder`");
    }

    public function down()
    {
        echo "m160227_123734_update_cashboxmoney cannot be reverted.\n";

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
