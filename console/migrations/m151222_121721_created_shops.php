<?php

use yii\db\Migration;
use yii\db\Schema;

class m151222_121721_created_shops extends Migration
{
    public function up()
    {
        $this->createTable('shops', [
            'id'            =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'name'          =>  Schema::TYPE_STRING,
            'type'          =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL'
        ]);

        $this->createTable('shopsGoods', [
            'shopID'        =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL',
            'itemID'        =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL',
            'count'         =>  Schema::TYPE_INTEGER.' UNSIGNED DEFAULT 0',
        ]);

        $this->createTable('shopsGoodsTransferringInvoices', [
            'id'            =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'shopFrom'      =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL',
            'shopTo'        =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL',
            'sendDate'      =>  Schema::TYPE_DATETIME,
            'receiveDate'   =>  Schema::TYPE_DATETIME,
            'sender'        =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL',
            'receiver'      =>  Schema::TYPE_INTEGER.' UNSIGNED DEFAULT 0',
        ]);

        $this->createTable('shopsGoodsTransferringItems', [
            'invoiceID'     =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL',
            'itemID'        =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL',
            'count'         =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL',
            'received'      =>  Schema::TYPE_SMALLINT.' UNSIGNED DEFAULT 0',
        ]);

        $this->addPrimaryKey('invoiceItem', 'shopsGoodsTransferringItems', ['invoiceID', 'itemID']);
    }

    public function down()
    {
        $this->dropTable('shops');
        $this->dropTable('shopsGoods');
        $this->dropTable('shopsGoodsTransferringInvoices');
        $this->dropTable('shopsGoodsTransferringItems');

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
