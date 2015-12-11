<?php

use yii\db\Migration;

class m151211_114835_receiverID_in_history extends Migration
{
    public function up()
    {
        $this->addColumn('history', 'receiverID', \yii\db\Schema::TYPE_BIGINT.' UNSIGNED DEFAULT 0');
    }

    public function down()
    {
        echo "m151211_114835_receiverID_in_history cannot be reverted.\n";

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
