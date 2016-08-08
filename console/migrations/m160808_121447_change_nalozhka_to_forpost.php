<?php

use yii\db\Migration;

class m160808_121447_change_nalozhka_to_forpost extends Migration
{
    public function up()
    {
        \common\models\DomainDeliveryPayment::updateAll(['enabled' => 1], ['paymentParam' => 6]);
        \common\models\DomainDeliveryPayment::updateAll(['enabled' => 0], ['paymentParam' => 5]);
    }

    public function down()
    {
        \common\models\DomainDeliveryPayment::updateAll(['enabled' => 0], ['paymentParam' => 6]);
        \common\models\DomainDeliveryPayment::updateAll(['enabled' => 1], ['paymentParam' => 5]);
        echo "m160808_121447_change_nalozhka_to_forpost was successfully reverted.\n";

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
