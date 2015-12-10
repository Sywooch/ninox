<?php

use yii\db\Migration;
use yii\db\Schema;

class m151209_151332_cancels_a_global_catastrophe extends Migration
{
    public function up()
    {
        $this->addColumn('partnersAddresses', 'name', Schema::TYPE_STRING.' DEFAULT NULL');
        $this->addColumn('partnersAddresses', 'surname', Schema::TYPE_STRING.' DEFAULT NULL');
        $this->addColumn('partnersAddresses', 'fathername', Schema::TYPE_STRING.' DEFAULT NULL');
        $this->addColumn('partnersAddresses', 'default', Schema::TYPE_SMALLINT.' UNSIGNED DEFAULT 0');
        $this->addColumn('partnersAddresses', 'contactRecipient', Schema::TYPE_STRING.' DEFAULT NULL');
        $this->alterColumn('partnersAddresses', 'partnerID', Schema::TYPE_BIGINT.' UNSIGNED DEFAULT 0');
        $this->alterColumn('partnersAddresses', 'ID', Schema::TYPE_BIGINT.' UNSIGNED DEFAULT 0');
        echo "  > trying to cancels a global catastrophe... \r \n";
        $this->execute("UPDATE `partnersAddresses` `a`, `partners` `b` SET `a`.`name` = SPLIT_STR(`b`.`Company`, ' ', 1), `a`.`surname` = SPLIT_STR(`b`.`Company`, ' ', 2), `a`.`fathername` = SPLIT_STR(`b`.`Company`, ' ', 3), `a`.`partnerID` = `b`.`ID`, `a`.`default` = 1 WHERE `a`.`partnerID` = `b`.`Code`");
        echo "  > all is ok? Wohoo! I'm hero! \r \n";
        $this->dropColumn("partnersContacts", 'contactRecipient');
        $this->addColumn('partnersContacts', 'partnerContactID', Schema::TYPE_BIGINT.' UNSIGNED DEFAULT 0');
        $this->alterColumn('partnersContacts', 'partnerID', Schema::TYPE_BIGINT.' UNSIGNED DEFAULT 0');
        $this->alterColumn('partnersContacts', 'ID', Schema::TYPE_BIGINT.' UNSIGNED DEFAULT 0');
        echo "  > some upgrades, baby! i change this world! \r \n";
        foreach(\common\models\Customer::find()->each(200) as $customer){
            \common\models\CustomerContacts::updateAll(['partnerID' => $customer->ID], ['partnerID' => $customer->Code]);
        }
        echo "  > i'm lost in space... \r \n";
        foreach(\common\models\CustomerContacts::find()->each(200) as $contact){
            $contact->ID = hexdec(uniqid());
            $contact->save(false);
        }
        echo "  > i'm doing it everyday... \r \n";
        foreach(\common\models\CustomerAddresses::find()->each(200) as $address){
            $address->ID = hexdec(uniqid());
            $address->save(false);
        }
        echo "  > please, wait some more, i'm working... \r \n";
        $this->execute("UPDATE `partnersContacts` `a`, `partnersAddresses` `b` SET `a`.`partnerContactID` = `b`.`ID` WHERE `a`.`partnerID` = `b`.`partnerID`");
        echo "  > thanks! \r \n";
    }

    public function down()
    {
        $this->dropColumn('partnersAddresses', 'name');
        $this->dropColumn('partnersAddresses', 'surname');
        $this->dropColumn('partnersAddresses', 'fathername');
        $this->dropColumn('partnersAddresses', 'default');
        $this->dropColumn('partnersAddresses', 'contactRecipient');
        $this->dropColumn('partnersContacts', 'partnerContactID');
        echo "  > only drop columns, not more \r \n";

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
