<?php

use yii\db\Migration;

class m160509_144551_order_provider_in_history extends Migration
{
    public function up()
    {
        $this->addColumn('history', 'orderProvider', \yii\db\Schema::TYPE_BIGINT.' UNSIGNED NOT NULL DEFAULT 0');
    }

    public function down()
    {
        $this->dropColumn('history', 'orderProvider');
        echo "m160509_144551_order_provider_in_history was successfully reverted.\n";
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
