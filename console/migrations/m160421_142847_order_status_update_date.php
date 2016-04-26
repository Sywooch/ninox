<?php

use yii\db\Migration;

class m160421_142847_order_status_update_date extends Migration
{
    public function up()
    {
        $this->addColumn('history', 'statusChangedDate', \yii\db\Schema::TYPE_DATETIME);
    }

    public function down()
    {
        $this->dropColumn('history', 'statusChangedDate');

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
