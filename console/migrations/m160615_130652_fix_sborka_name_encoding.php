<?php

use yii\db\Migration;

class m160615_130652_fix_sborka_name_encoding extends Migration
{
    public function up()
    {
        $this->alterColumn('sborka', 'name', \yii\db\Schema::TYPE_TEXT." CHARACTER SET utf8 COLLATE utf8_general_ci NULL");
    }

    public function down()
    {
        echo "m160615_130652_fix_sborka_name_encoding cannot be reverted.\n";

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
