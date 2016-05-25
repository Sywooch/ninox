<?php

use yii\db\Migration;

class m160525_151535_change_bank_name_privat extends Migration
{
    public function up()
    {
        \common\models\PaymentParam::updateAll(['description' => 'ПриватБанк'], ['id' => 1]);
    }

    public function down()
    {
        \common\models\PaymentParam::updateAll(['description' => 'Приват банк'], ['id' => 1]);
        echo "m160525_151535_change_bank_name_privat was successfully reverted.\n";

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
