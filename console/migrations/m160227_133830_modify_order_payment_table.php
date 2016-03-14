<?php

use yii\db\Migration;
use yii\db\Schema;

class m160227_133830_modify_order_payment_table extends Migration
{
    public function up()
    {
	    $this->alterColumn('orderPayments', 'paymentID', Schema::TYPE_INTEGER.' UNSIGNED NOT NULL AUTO_INCREMENT');
	    $this->alterColumn('orderPayments', 'paymentDate', Schema::TYPE_DATETIME.' NOT NULL');
	    $this->alterColumn('orderPayments', 'confirmationDate', Schema::TYPE_DATETIME.' NOT NULL');
	    $this->alterColumn('history', 'moneyConfirmed', Schema::TYPE_SMALLINT.' UNSIGNED NOT NULL DEFAULT 0');
	    $this->alterColumn('history', 'moneyConfirmedDate', Schema::TYPE_DATETIME.' NOT NULL');
	    $this->addColumn('orderPayments', 'responsibleUser', Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0');
	    $this->renameColumn('orderPayments', 'paymentID', 'ID');
	    $this->renameColumn('orderPayments', 'paymentType', 'type');
	    $this->renameColumn('orderPayments', 'paymentParam', 'param');
	    $this->renameColumn('orderPayments', 'paymentDate', 'date');
	    $this->renameColumn('orderPayments', 'paymentAmount', 'amount');
    }

    public function down()
    {
	    $this->dropColumn('orderPayments', 'responsibleUser');
	    $this->alterColumn('orderPayments', 'ID', Schema::TYPE_INTEGER.' UNSIGNED NOT NULL');
	    $this->alterColumn('orderPayments', 'date', Schema::TYPE_DATETIME);
	    $this->alterColumn('orderPayments', 'confirmationDate', Schema::TYPE_DATETIME);
	    $this->alterColumn('history', 'moneyConfirmed', Schema::TYPE_INTEGER);
	    $this->alterColumn('history', 'moneyConfirmedDate', Schema::TYPE_DATETIME);
	    $this->renameColumn('orderPayments', 'ID', 'paymentID');
	    $this->renameColumn('orderPayments', 'type', 'paymentType');
	    $this->renameColumn('orderPayments', 'param', 'paymentParam');
	    $this->renameColumn('orderPayments', 'date', 'paymentDate');
	    $this->renameColumn('orderPayments', 'amount', 'paymentAmount');
        echo "m160227_133830_modify_order_payment_table was successful reverted.\n";
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
