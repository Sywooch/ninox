<?php

use yii\db\Migration;

class m160320_151506_update_domain_config_options extends Migration
{
    public function up()
    {
        $this->update('deliveryParams', ['options' => '/img/site/order/delivery/np.png'], ['id' => 1]);
        $this->update('deliveryParams', ['options' => '/img/site/order/delivery/it.png'], ['id' => 2]);
        $this->update('domains_delivery_payments',
            [
                'options' => '{"content":"address","commissions":{"static":"20","percent":"2"}}'
            ],
            [
                'domainId' => 1,
                'deliveryType' => 1,
                'deliveryParam' => 1,
                'paymentType' => 1,
                'paymentParam' => 0
            ]);
        $this->update('domains_delivery_payments',
            [
                'options' => '{"content":"address","commissions":{"static":"0","percent":"0"}}'
            ],
            [
                'domainId' => 1,
                'deliveryType' => 1,
                'deliveryParam' => 1,
                'paymentType' => 2,
                'paymentParam' => 1
            ]);
        $this->update('domains_delivery_payments',
            [
                'options' => '{"content":"department","commissions":{"static":"20","percent":"2"}}'
            ],
            [
                'domainId' => 1,
                'deliveryType' => 2,
                'deliveryParam' => 1,
                'paymentType' => 1,
                'paymentParam' => 0
            ]);
        $this->update('domains_delivery_payments',
            [
                'options' => '{"content":"department","commissions":{"static":"0","percent":"0"}}'
            ],
            [
                'domainId' => 1,
                'deliveryType' => 2,
                'deliveryParam' => 1,
                'paymentType' => 2,
                'paymentParam' => 1
            ]);
        $this->update('domains_delivery_payments',
            [
                'options' => '{"content":"stock","commissions":{"static":"0","percent":"0"}}'
            ],
            [
                'domainId' => 1,
                'deliveryType' => 3,
                'deliveryParam' => 4,
                'paymentType' => 2,
                'paymentParam' => 1
            ]);
        $this->update('domains_delivery_payments',
            [
                'options' => '{"content":"stock","commissions":{"static":"0","percent":"0"}}'
            ],
            [
                'domainId' => 1,
                'deliveryType' => 3,
                'deliveryParam' => 4,
                'paymentType' => 3,
                'paymentParam' => 0
            ]);
    }

    public function down()
    {
        echo "m160320_151506_update_domain_config_options cannot be reverted.\n";
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
