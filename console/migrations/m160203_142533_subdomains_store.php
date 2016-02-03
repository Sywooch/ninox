<?php

use yii\db\Migration;

class m160203_142533_subdomains_store extends Migration
{
    public function up()
    {
        $this->addColumn('subDomains', 'storeId', \yii\db\Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0');
    }

    public function down()
    {
        $this->dropColumn('subDomains', 'storeId');
        
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
