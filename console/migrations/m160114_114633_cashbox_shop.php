<?php

use yii\db\Migration;

class m160114_114633_cashbox_shop extends Migration
{
    public function up()
    {
        $this->addColumn('cashbox', 'store', \yii\db\Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0');
    }

    public function down()
    {
        $this->dropColumn('cashbox', 'store');
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
