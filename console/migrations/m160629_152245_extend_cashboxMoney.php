<?php

use yii\db\Migration;

class m160629_152245_extend_cashboxMoney extends Migration
{
    public function up()
    {
        $this->alterColumn(\common\models\CashboxMoney::tableName(), 'amount', $this->float(2));
    }

    public function down()
    {
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
