<?php

use yii\db\Migration;

class m160710_134505_update_order_to_createdOrderID_in_cashboxMoney extends Migration
{
    public function up()
    {
		$this->execute("UPDATE `cashboxOrders`, `cashboxMoney` SET `cashboxMoney`.`order` = `cashboxOrders`.`createdOrderID`
			WHERE `cashboxMoney`.`order` = `cashboxOrders`.`id`");
    }

    public function down()
    {
	    $this->execute("UPDATE `cashboxOrders`, `cashboxMoney` SET `cashboxMoney`.`order` = `cashboxOrders`.`id`
			WHERE `cashboxMoney`.`order` = `cashboxOrders`.`createdOrderID`");

        echo "m160710_134505_update_order_to_createdOrderID_in_cashboxMoney was successfully reverted.\n";
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
