<?php

use yii\db\Migration;

class m160226_115856_cashbox_items_order extends Migration
{
    public function up()
    {
        $this->addColumn('cashboxItems', 'added', \yii\db\Schema::TYPE_TIMESTAMP);
    }

    public function down()
    {
        return $this->dropColumn('cashboxItems', 'added');
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
