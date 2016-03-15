<?php

use yii\db\Migration;

class m160227_125352_change_added_type_in_cashboxItem extends Migration
{
    public function up()
    {
        $this->alterColumn('cashboxItems', 'added', \yii\db\Schema::TYPE_DATETIME);
    }

    public function down()
    {
        $this->alterColumn('cashboxItems', 'added', \yii\db\Schema::TYPE_TIMESTAMP);

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
