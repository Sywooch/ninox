<?php

use yii\db\Migration;

class m160519_112150_fix_send_pay_table extends Migration
{
    public function up()
    {
        $this->alterColumn('send_pay', 'kvitanciya', \yii\db\Schema::TYPE_STRING."(255) NOT NULL DEFAULT ''");
    }

    public function down()
    {
        echo "m160519_112150_fix_send_pay_table cannot be reverted.\n";
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
