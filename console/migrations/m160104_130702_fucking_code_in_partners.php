<?php

use yii\db\Migration;

class m160104_130702_fucking_code_in_partners extends Migration
{
    public function up()
    {
        $this->alterColumn('partners', 'Code', \yii\db\Schema::TYPE_INTEGER.' UNSIGNED DEFAULT NULL');
    }

    public function down()
    {
        $this->alterColumn('partners', 'Code', \yii\db\Schema::TYPE_STRING);

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
