<?php

use yii\db\Migration;
use yii\db\Schema;

class m151223_125956_cashboxItems extends Migration
{
    public function up()
    {
        $this->createTable('cashboxItems', [
            'itemID'        =>  Schema::TYPE_BIGINT.' UNSIGNED NOT NULL',
            'orderID'       =>  Schema::TYPE_BIGINT.' UNSIGNED NOT NULL',
            'name'          =>  Schema::TYPE_STRING,
            'count'         =>  Schema::TYPE_INTEGER.' UNSIGNED DEFAULT 0',
            'originalPrice' =>  Schema::TYPE_DOUBLE.'(7,2) UNSIGNED DEFAULT 0',
            'discountType'  =>  Schema::TYPE_INTEGER.' UNSIGNED DEFAULT 0',
            'discountSize'  =>  Schema::TYPE_INTEGER.' UNSIGNED DEFAULT 0',
            'priceRuleID'   =>  Schema::TYPE_INTEGER.' UNSIGNED DEFAULT 0',
            'category'      =>  Schema::TYPE_STRING,
            'customerRule'  =>  Schema::TYPE_SMALLINT.' UNSIGNED DEFAULT 0',
            'deleted'       =>  Schema::TYPE_SMALLINT.' UNSIGNED DEFAULT 0',
        ]);

        $this->createTable('cashboxOrders', [
            'id'                =>  Schema::TYPE_BIGINT.' UNSIGNED NOT NULL PRIMARY KEY',
            'customerID'        =>  Schema::TYPE_BIGINT.' UNSIGNED NOT NULL',
            'responsibleUser'   =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL',
            'createdTime'       =>  Schema::TYPE_DATETIME,
            'doneTime'          =>  Schema::TYPE_DATETIME,
            'priceType'         =>  Schema::TYPE_SMALLINT.' UNSIGNED DEFAULT 0',
            'deleted'           =>  Schema::TYPE_SMALLINT.' UNSIGNED DEFAULT 0',
        ]);
    }

    public function down()
    {

        $this->dropTable('cashboxItems');
        $this->dropTable('cashboxOrders');

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
