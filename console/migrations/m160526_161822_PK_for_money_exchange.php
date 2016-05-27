<?php

use yii\db\Migration;

class m160526_161822_PK_for_money_exchange extends Migration
{
    public function up()
    {
        $this->alterColumn(\common\models\MoneyExchange::tableName(), 'date', \yii\db\Schema::TYPE_DATE);
        $this->addPrimaryKey('date', \common\models\MoneyExchange::tableName(), 'date');
    }

    public function down()
    {
        echo "m160526_161822_PK_for_money_exchange cannot be reverted.\n";

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
