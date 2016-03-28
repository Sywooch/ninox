<?php

use yii\db\Migration;

class m160227_141739_fill_order_payment_table extends Migration
{
    public function up()
    {
        $orderCount = \common\models\History::find(['deleted' => 0])->count();
        $i = 0;
        echo "    > prepare array for batch insert into orderPayments for ".$orderCount." orders... \r \n";
        $orderPayments = [];
        foreach(\backend\models\History::find(['deleted' => 0])->each(100) as $model){
            $orderPayments[] = [
                $model->id,
                $model->paymentType,
                $model->paymentParam,
                $model->moneyConfirmedDate,
                $model->actualAmount,
                $model->moneyConfirmed,
                $model->moneyConfirmedDate,
                $model->moneyCollectorUserId
            ];
            echo "    > order ".++$i." from ".$orderCount." \r \n";
	    }

        $this->batchInsert('orderPayments', [
            'orderID',
            'type',
            'param',
            'date',
            'amount',
            'confirmed',
            'confirmationDate',
            'responsibleUser'],
            $orderPayments
        );
    }

    public function down()
    {
        $this->truncateTable('orderPayments');
        echo "m160227_141739_fill_order_payment_table was successful reverted.\n";
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
