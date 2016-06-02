<?php

use yii\db\Migration;
use yii\db\Schema;

class m160602_072309_goods_default_value_fix extends Migration
{
    public function up()
    {
        $this->alterColumn('goods', 'otgruzka', Schema::TYPE_SMALLINT." UNSIGNED NOT NULL DEFAULT 0");
        $this->alterColumn('goods', 'rate', Schema::TYPE_FLOAT." UNSIGNED NOT NULL DEFAULT 0");
    }

    public function down()
    {
        echo "m160602_072309_goods_default_value_fix cannot be reverted.\n";

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
