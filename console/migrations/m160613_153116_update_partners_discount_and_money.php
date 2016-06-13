<?php

use yii\db\Migration;

class m160613_153116_update_partners_discount_and_money extends Migration
{
    public function up()
    {
        $this->alterColumn('partners', 'money', \yii\db\Schema::TYPE_DOUBLE.'(24, 2) NULL DEFAULT 0');
        \common\models\Customer::updateAll(['discount' => 2], ['>=', 'cardNumber', '90000000']);
    }

    public function down()
    {
        echo "m160613_153116_update_partners_discount_and_money cannot be reverted.\n";
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
