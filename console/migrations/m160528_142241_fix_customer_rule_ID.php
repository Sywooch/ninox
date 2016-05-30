<?php

use yii\db\Migration;

class m160528_142241_fix_customer_rule_ID extends Migration
{
    public function up()
    {
        $this->alterColumn('sborka', 'customerRule', \yii\db\Schema::TYPE_SMALLINT." NOT NULL DEFAULT 0");
    }

    public function down()
    {

        $this->alterColumn('sborka', 'customerRule', \yii\db\Schema::TYPE_SMALLINT." UNSIGNED NOT NULL DEFAULT 0");

        echo "m160528_142241_fix_customer_rule_ID was successfully reverted.\n";

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
