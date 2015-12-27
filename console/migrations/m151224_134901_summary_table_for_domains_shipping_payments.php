<?php

use yii\db\Migration;
use yii\db\Schema;

class m151224_134901_summary_table_for_domains_shipping_payments extends Migration
{
    public function up()
    {
		$this->createTable('domains_shipping_payments', [
			'domainID'  =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL',
			'shippingID'  =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL',
			'paymentID'  =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL',
			'paymentParam'  =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL',
			'enable'  =>  Schema::TYPE_SMALLINT.' UNSIGNED NOT NULL DEFAULT 0'
		]);

	    $this->addPrimaryKey('domain', 'domains_shipping_payments', ['domainID', 'shippingID', 'paymentID', 'paymentParam']);
    }

    public function down()
    {
		$this->dropTable('domains_shipping_payments');
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
