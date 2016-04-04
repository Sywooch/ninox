<?php

use common\models\Customer;
use common\models\CustomerAddresses;
use yii\db\Migration;
use yii\db\Schema;

class m160404_151349_customers_np extends Migration
{
    public function up()
    {
        $this->addColumn(Customer::tableName(), 'cityCode', Schema::TYPE_STRING);

        $this->renameColumn(CustomerAddresses::tableName(), 'shippingType', 'deliveryType');
        $this->renameColumn(CustomerAddresses::tableName(), 'shippingParam', 'deliveryParam');
        $this->renameColumn(CustomerAddresses::tableName(), 'contactRecipient', 'contactID');
        $this->renameColumn(CustomerAddresses::tableName(), 'cityRecipient', 'cityID');
        $this->addColumn(CustomerAddresses::tableName(), 'deliveryInfo', Schema::TYPE_STRING);
        $this->addColumn(CustomerAddresses::tableName(), 'paymentInfo', Schema::TYPE_STRING);
    }

    public function down()
    {
        $this->dropColumn(Customer::tableName(), 'cityCode');


        $this->renameColumn(CustomerAddresses::tableName(), 'deliveryType', 'shippingType');
        $this->renameColumn(CustomerAddresses::tableName(), 'deliveryParam', 'shippingParam');
        $this->renameColumn(CustomerAddresses::tableName(), 'contactID', 'contactRecipient');
        $this->renameColumn(CustomerAddresses::tableName(), 'cityID', 'cityRecipient');
        $this->dropColumn(CustomerAddresses::tableName(), 'deliveryInfo');
        $this->dropColumn(CustomerAddresses::tableName(), 'paymentInfo');

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
