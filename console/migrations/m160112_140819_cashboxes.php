<?php

use yii\db\Migration;
use yii\db\Schema;

class m160112_140819_cashboxes extends Migration
{
    public function up()
    {
        $this->createTable('cashbox', [
            'ID'            =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'domain'        =>  Schema::TYPE_STRING,
            'autologin'     =>  Schema::TYPE_TEXT,
            'name'          =>  Schema::TYPE_STRING,
            'created'       =>  Schema::TYPE_DATETIME,
            'lastOrderTime' =>  Schema::TYPE_DATETIME,
            'lastOrder'     =>  Schema::TYPE_BIGINT.' UNSIGNED NOT NULL DEFAULT 0',
            'lastManager'   => Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0',
            'defaultCustomer'=> Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0',
            'defaultWholesaleCustomer'=>    Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0'
        ]);

        $this->createTable('cashboxMoney', [
            'ID'            =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'cashbox'       =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0',
            'operation'     =>  Schema::TYPE_SMALLINT.' UNSIGNED NOT NULL DEFAULT 0',
            'amount'        =>  Schema::TYPE_DOUBLE.' (7,2) NOT NULL DEFAULT 0',
            'date'          =>  Schema::TYPE_DATETIME,
            'order'         =>  Schema::TYPE_BIGINT.' UNSIGNED NOT NULL DEFAULT 0',
            'customer'      =>  Schema::TYPE_BIGINT.' UNSIGNED NOT NULL DEFAULT 0',
            'responsibleUser'   =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0'
        ]);
    }

    public function down()
    {
        $this->dropTable('cashbox');
        $this->dropTable('cashboxMoney');

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
