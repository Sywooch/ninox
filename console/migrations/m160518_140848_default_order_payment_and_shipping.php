<?php

use yii\db\Migration;

class m160518_140848_default_order_payment_and_shipping extends Migration
{
    public function up()
    {
        $this->addColumn('domains_delivery_payments', 'default', \yii\db\Schema::TYPE_SMALLINT.' UNSIGNED NOT NULL DEFAULT 0');
        $default = \common\models\DomainDeliveryPayment::find()->where([
            'domainId'  =>  1,
            'deliveryType'  =>  2,
            'deliveryParam'  =>  1,
            'paymentType'  =>  2,
            'paymentParam'  =>  1,
            'enabled'  =>  1,
        ])->one();
        if($default){
            $default->default = 1;
            $default->save(false);
        }
    }

    public function down()
    {
        $this->dropColumn('domains_delivery_payments', 'default');

        echo "m160518_140848_default_order_payment_and_shipping was successfully reverted.\n";

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
