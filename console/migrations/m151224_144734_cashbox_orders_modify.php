<?php

use yii\db\Migration;
use yii\db\Schema;

class m151224_144734_cashbox_orders_modify extends Migration
{
    public function up()
    {
	    $this->addColumn('cashboxOrders', 'source', Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0');
    }

    public function down()
    {
	    $this->dropColumn('cashboxOrders', 'source');
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
