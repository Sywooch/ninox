<?php

use yii\db\Migration;

class m160504_141445_fix_customerID_in_reviews extends Migration
{
    public function up()
    {
        $this->alterColumn('reviews', 'customerID', \yii\db\Schema::TYPE_BIGINT.' UNSIGNED NOT NULL');
    }

    public function down()
    {
        echo "m160504_141445_fix_customerID_in_reviews was successfully reverted.\n";
        $this->alterColumn('reviews', 'customerID', \yii\db\Schema::TYPE_INTEGER);
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
