<?php

use yii\db\Migration;

class m160527_082928_fix_null_param_for_callback extends Migration
{
    public function up()
    {
        $this->alterColumn('callback', 'question', \yii\db\Schema::TYPE_STRING."(1000) NOT NULL DEFAULT ''");
        $this->alterColumn('callback', 'customerName', \yii\db\Schema::TYPE_STRING."(50) NOT NULL DEFAULT ''");
    }

    public function down()
    {
        echo "m160527_082928_fix_null_param_for_callback cannot be reverted.\n";

        return false;
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
