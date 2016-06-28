<?php

use yii\db\Migration;

class m160628_112435_forpost_payment_update extends Migration
{
    public function up()
    {
        $this->batchInsert('paymentParams', ['description', 'value', 'options', 'enabled'],[
            ['Конвертик', '', '', '1'],
            ['Forpost', '', '', '0'],
        ]);

        $this->update('domains_delivery_payments',
            [
                'paymentParam' => 5
            ],
            [
                'paymentType' => 1,
                'paymentParam' => 0
            ]);

        $this->batchInsert('domains_delivery_payments', ['domainId', 'deliveryType', 'deliveryParam', 'paymentType', 'paymentParam', 'options', 'enabled', 'default'], [
            [1, 1, 1, 1, 6, '{"content":"address","commissions":{"static":"20","percent":"2"}}', 0, 0],
            [1, 2, 1, 1, 6, '{"content":"department","commissions":{"static":"20","percent":"2"}}', 0, 0],
        ]);

        \common\models\History::updateAll(['paymentParam' => 5], ['paymentType' => 1, 'globalmoney' => 0]);
        \common\models\History::updateAll(['paymentParam' => 6], ['paymentType' => 1, 'globalmoney' => 1]);
    }

    public function down()
    {
        echo "m160628_112435_forpost_payment_update cannot be reverted.\n";

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
