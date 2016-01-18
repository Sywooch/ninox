<?php

use yii\db\Migration;
use yii\db\Schema;

class m160114_132815_history_source_info extends Migration
{
    public function up()
    {
	    $this->addColumn('history', 'sourceInfo', Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0');
    }

    public function down()
    {
        $this->dropColumn('history', 'sourceInfo');
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
