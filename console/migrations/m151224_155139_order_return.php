<?php

use yii\db\Migration;

class m151224_155139_order_return extends Migration
{
    public function up()
    {
        $this->addColumn('history', 'return', \yii\db\Schema::TYPE_SMALLINT.' UNSIGNED NOT NULL DEFAULT 0');
    }

    public function down()
    {
        return $this->dropColumn('history', 'return');
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
