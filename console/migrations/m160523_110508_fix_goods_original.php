<?php

use yii\db\Migration;

class m160523_110508_fix_goods_original extends Migration
{
    public function up()
    {
        $this->alterColumn('goods', 'originalGood', \yii\db\Schema::TYPE_SMALLINT." UNSIGNED NOT NULL DEFAULT 0");
    }

    public function down()
    {
        echo "m160523_110508_fix_goods_original cannot be reverted.\n";

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
