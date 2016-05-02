<?php

use yii\db\Migration;

class m160421_144959_item_unlimited_fix extends Migration
{
    public function up()
    {
        $this->alterColumn('goods', 'isUnlimited', \yii\db\Schema::TYPE_SMALLINT.' UNSIGNED NOT NULL DEFAULT 0');
    }

    public function down()
    {
        echo "m160421_144959_item_unlimited_fix cannot be reverted.\n";

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
