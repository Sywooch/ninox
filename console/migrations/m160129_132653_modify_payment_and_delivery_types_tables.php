<?php

use yii\db\Migration;
use yii\db\Schema;

class m160129_132653_modify_payment_and_delivery_types_tables extends Migration
{
    public function up()
    {
	    $this->renameColumn('deliveryTypes', 'replaceDescription', 'modifyLabel');
	    $this->addColumn('paymentTypes', 'modifyLabel', Schema::TYPE_SMALLINT.' UNSIGNED NOT NULL DEFAULT 0');
	    $this->update('paymentTypes', ['modifyLabel' => 2], ['id' => 2]);
    }

    public function down()
    {
	    $this->renameColumn('deliveryTypes', 'modifyLabel', 'replaceDescription');
	    $this->dropColumn('paymentTypes', 'modifyLabel');
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
