<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 29.01.16
 * Time: 17:56
 */

namespace backend\components;


use backend\models\History;

class Sms extends \common\components\Sms{

    const MESSAGE_PAYMENT_CONFIRMED_ID = 420750;        // Заказ оплачен
    const MESSAGE_CANT_CALL_ID = 420753;                // Не смогли дозвониться
    const MESSAGE_ORDER_DONE_ID = 420754;               // Заказ готов (оплата на карту)
    const MESSAGE_ORDER_WAIT_DELIVERY_ID = 420755;      // Заказ собран

    /**
     * @param $order History
     * @param $messageID integer
     *
     * @return int|string
     */
    public function sendPreparedMessage($order, $messageID){
        $message = new \stdClass();

        $message->recipients = [$order->customerPhone];
        $message->params = [
            ['key' => 'ORDERID', 'value' => $order->number],
            ['key' => 'ACTUALAMOUNT', 'value' => $order->actualAmount],
            ['key' => 'BANKNAME', 'value' => $order->paymentParamInfo->description],
            ['key' => 'CARDNUMBER', 'value' => $order->paymentParamInfo->value],
            ['key' => 'CARDHOLDER', 'value' => $order->paymentParamInfo->options],
        ];

        return $this->send([
            'action'    =>  'message',
            'data'      =>  $message
        ], $messageID.'/send');
    }

}