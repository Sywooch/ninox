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

    const MESSAGE_CANT_CALL_ID = 420753;                // Не смогли дозвониться
    const MESSAGE_ORDER_DONE_CARD_ID = 420754;          // Заказ собран (оплата на карту)
    const MESSAGE_ORDER_DONE_COD_ID = 420755;           // Заказ собран (наложка)
    const MESSAGE_ORDER_DONE_PICKUP_ID = 442349;        // Заказ собран (самовывоз)
    const MESSAGE_ORDER_DELIVERED_ID = 442219;          // Заказ отправлен (ТТН)

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
            ['key' => 'TTN', 'value' => $order->nakladna],
            ['key' => 'NOVAPOSHTA', 'value' => $order->deliveryInfo],
        ];

        if($order->paymentType == 2){
            $message->params = array_merge($message->params, [
                ['key' => 'BANKNAME', 'value' => $order->paymentParamInfo->description],
                ['key' => 'CARDNUMBER', 'value' => $order->paymentParamInfo->value],
                ['key' => 'CARDHOLDER', 'value' => $order->paymentParamInfo->options],
            ]);
        }

        return $this->send([
            'action'    =>  'message',
            'data'      =>  $message
        ], $messageID.'/send');
    }

    public function getMessageDescription($id){
        if(!array_key_exists($id, $this->descriptions)){
            return '';
        }

        return $this->descriptions[$id];
    }

    public function getDescriptions(){
        return[
            self::MESSAGE_CANT_CALL_ID          =>  'о недозвоне',
            self::MESSAGE_ORDER_DONE_CARD_ID    =>  'с номером карты',
            self::MESSAGE_ORDER_DONE_COD_ID     =>  'о готовности заказа',
            self::MESSAGE_ORDER_DONE_PICKUP_ID  =>  'о самовывозе',
            self::MESSAGE_ORDER_DELIVERED_ID    =>  'с номером ТТН',
        ];
    }

}