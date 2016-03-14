<?php

use yii\db\Migration;

class m160213_124547_promocodes_update extends Migration
{
    public function up()
    {
        $this->alterColumn('promocodes', 'code', \yii\db\Schema::TYPE_STRING);
        $this->addColumn('cashboxOrders', 'promoCode', \yii\db\Schema::TYPE_STRING);
    }

    public function down()
    {
        $this->dropColumn('cashboxOrders', 'promoCode');

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
