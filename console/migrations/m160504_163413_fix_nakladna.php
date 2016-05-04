<?php

use yii\db\Migration;

class m160504_163413_fix_nakladna extends Migration
{
    public function up()
    {
        $this->alterColumn('history', 'nakladna', \yii\db\Schema::TYPE_STRING."(32) DEFAULT NULL");
        $this->alterColumn('history', 'moneyConfirmedDate', \yii\db\Schema::TYPE_DATETIME." DEFAULT NULL");
    }

    public function down()
    {
        echo "m160504_163413_fix_nakladna cannot be reverted.\n";

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
