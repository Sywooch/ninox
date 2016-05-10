<?php

use yii\db\Migration;

class m160510_084000_partners_addresses_search_improvment extends Migration
{
    public function up()
    {
        $this->createIndex('partnerID', 'partnersAddresses', 'partnerID');
    }

    public function down()
    {
        $this->dropIndex('partnerID', 'partnersAddresses');
        echo "m160510_084000_partners_addresses_search_improvment was successfully reverted.\n";

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
