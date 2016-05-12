<?php

use yii\db\Migration;
use yii\db\Schema;

class m151207_113457_customers_rename extends Migration
{
    public function up()
    {
        //$this->renameColumn('partners', '')
        $this->dropColumn('partners', 'Company2');
        $this->dropColumn('partners', 'MOL');
        $this->dropColumn('partners', 'Fax');
        $this->dropColumn('partners', 'MOL2');
        $this->dropColumn('partners', 'VerifiedCity');
        $this->dropColumn('partners', 'Address2');
        $this->dropColumn('partners', 'TaxNo');
        $this->dropColumn('partners', 'Bulstat');
        $this->dropColumn('partners', 'BankName');
        $this->dropColumn('partners', 'BankCode');
        $this->dropColumn('partners', 'BankAcct');
        $this->dropColumn('partners', 'BankVATName');
        $this->dropColumn('partners', 'BankVATCode');
        $this->dropColumn('partners', 'BankVATAcct');
        $this->dropColumn('partners', 'IsVeryUsed');
        $this->dropColumn('partners', 'UserID');
        $this->dropColumn('partners', 'Note1');
        $this->dropColumn('partners', 'PaymentDays');
        $this->dropColumn('partners', 'utma');
        $this->dropColumn('partners', 'cityID');
        $this->renameColumn('partners', 'Discount', 'discount');
        $this->renameColumn('partners', 'Phone', 'phone');
        $this->renameColumn('partners', 'eMail', 'email');
        $this->renameColumn('partners', 'PriceGroup', 'priceGroup');
        $this->renameColumn('partners', 'Type', 'type');
        $this->renameColumn('partners', 'GroupID', 'groupID');
        $this->renameColumn('partners', 'UserRealTime', 'registrationTime');
        $this->renameColumn('partners', 'Deleted', 'deleted');
        $this->renameColumn('partners', 'CardNumber', 'cardNumber');
        $this->renameColumn('partners', 'ShippingType', 'shippingType');
        $this->renameColumn('partners', 'black', 'blackList');
        $this->renameColumn('partners', 'blackDate', 'blackListAddedTime');
        $this->addColumn('partners', 'password_reset_token', \yii\db\Schema::TYPE_STRING.' DEFAULT NULL');
    }

    public function down()
    {
        echo "m151207_113457_customers_rename cannot be reverted.\n";

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
