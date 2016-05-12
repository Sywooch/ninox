<?php

use yii\db\Migration;
use yii\db\Schema;

class m160329_160611_improve_partners_table extends Migration
{
    public function up()
    {
        $this->addColumn('partners', 'deliveryParam', Schema::TYPE_INTEGER.' UNSIGNED DEFAULT NULL');
        $this->addColumn('partners', 'deliveryInfo', Schema::TYPE_STRING);
        $this->addColumn('partners', 'paymentParam', Schema::TYPE_INTEGER.' UNSIGNED DEFAULT NULL');
        $this->addColumn('partners', 'paymentInfo', Schema::TYPE_STRING);

        $this->renameColumn('partners', 'PaymentType', 'paymentType');
        $this->renameColumn('partners', 'ShippingType', 'deliveryType');
        $this->alterColumn('partners', 'paymentType', Schema::TYPE_INTEGER.' UNSIGNED DEFAULT NULL');
        $this->alterColumn('partners', 'deliveryType', Schema::TYPE_INTEGER.' UNSIGNED DEFAULT NULL');
    }


    public function down()
    {
        echo "m160329_160611_improve_partners_table cannot be reverted.\n";

        return false;
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
