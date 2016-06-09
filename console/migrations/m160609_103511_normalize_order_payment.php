<?php

use yii\db\Migration;

class m160609_103511_normalize_order_payment extends Migration
{
    public function up()
    {
        foreach(\common\models\SendedPayment::find()->each() as $payment){
            $payment->nomer_id = filter_var($payment->nomer_id, FILTER_SANITIZE_NUMBER_INT);

            $payment->save(false);
        }
    }

    public function down()
    {
        echo "m160609_103511_normalize_order_payment cannot be reverted.\n";

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
