<?php

use yii\db\Migration;
use yii\db\Schema;

class m151228_122707_postpone_checks extends Migration
{
    public function up()
    {
        $this->addColumn('cashboxOrders', 'postpone', Schema::TYPE_SMALLINT.' UNSIGNED NOT NULL DEFAULT 0');
    }

    public function down()
    {

        return $this->dropColumn('cashboxOrders', 'postpone');
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
