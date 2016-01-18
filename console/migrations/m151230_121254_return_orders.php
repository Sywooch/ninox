<?php

use yii\db\Migration;

class m151230_121254_return_orders extends Migration
{
    public function up()
    {
        $this->addColumn('cashboxOrders', 'return', \yii\db\Schema::TYPE_SMALLINT.' UNSIGNED DEFAULT 0');
    }

    public function down()
    {
        $this->dropColumn('cashboxOrders', 'return');

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
