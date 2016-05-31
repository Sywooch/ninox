<?php

use yii\db\Migration;

class m160526_123519_fix_zero_payment_and_delivery_params_in_orders extends Migration
{
    public function up()
    {
        \common\models\History::updateAll(['deliveryParam' => 1], ['deliveryType' => [1, 2], 'deliveryParam' => 0]);
        \common\models\History::updateAll(['paymentParam' => 1], ['paymentType' => 2, 'paymentParam' => 0]);
    }

    public function down()
    {
        echo "m160526_123519_fix_zero_payment_and_delivery_params_in_orders cannot be reverted.\n";
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
