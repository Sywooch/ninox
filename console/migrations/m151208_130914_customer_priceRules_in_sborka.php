<?php

use yii\db\Migration;
use yii\db\Schema;

class m151208_130914_customer_priceRules_in_sborka extends Migration
{
    public function up()
    {
	    $this->addColumn('sborka', 'customerRule', Schema::TYPE_SMALLINT.' UNSIGNED NOT NULL DEFAULT 0');
    }

    public function down()
    {
        $this->dropColumn('sborka', 'customerRule');
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
