<?php

use yii\db\Migration;
use yii\db\Schema;

class m160131_110338_cashboxes_fix extends Migration
{
    public function up()
    {
        $this->addColumn('cashbox', 'default', Schema::TYPE_SMALLINT.' UNSIGNED NOT NULL DEFAULT 0');
        $this->dropColumn('cashbox', 'domain');
        $this->dropColumn('cashbox', 'autologin');
    }

    public function down()
    {
        $this->dropColumn('cashbox', 'default');
        $this->addColumn('cashbox', 'domain', Schema::TYPE_STRING);
        $this->addColumn('cashbox', 'autologin', Schema::TYPE_TEXT);

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
