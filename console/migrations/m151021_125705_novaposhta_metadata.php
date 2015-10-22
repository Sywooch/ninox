<?php

use yii\db\Migration;

class m151021_125705_novaposhta_metadata extends Migration
{
    public function up()
    {
        $this->addColumn('partnersAddresses', 'cityRecipient', \yii\db\mysql\Schema::TYPE_STRING.' DEFAULT NULL');
        $this->addColumn('partnersAddresses', 'recipientAddress', \yii\db\mysql\Schema::TYPE_STRING.' DEFAULT NULL');
        $this->addColumn('partnersContacts', 'contactRecipient', \yii\db\mysql\Schema::TYPE_STRING.' DEFAULT NULL');
        $this->addColumn('partners', 'recipientID', \yii\db\mysql\Schema::TYPE_STRING.' DEFAULT NULL');
    }

    public function down()
    {
        $this->dropColumn('partnersAddresses', 'cityRecipient');
        $this->dropColumn('partnersAddresses', 'recipientAddress');
        $this->dropColumn('partnersContacts', 'contactRecipient');
        $this->dropColumn('partners', 'recipientID');
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
