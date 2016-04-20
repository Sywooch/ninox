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

    const MESSAGE_CANT_CALL_ID = 420753;
    const MESSAGE_PAYMENT_CONFIRMED_ID = 420750;
    const MESSAGE_ORDER_DONE_ID = 420754;


    public function sendTtn($ttn){

    }

    /**
     * @param $order History
     *
     * @return int|string
     */
    public function sendCantCall($order){
        $message = new \stdClass();

        $message->recipients = [$order->customerPhone];
        $message->params = [
            ['key' => 'ORDERID', 'value' => $order->number],
            ['key' => 'ORIGINALSUM', 'value' => $order->originalSum],
        ];

        return $this->send([
            'action'    =>  'message',
            'data'      =>  $message
        ], self::MESSAGE_CANT_CALL_ID.'/send');
    }

    /**
     * @param $order History
     *
     * @return int|string
     */
    public function sendNotPayed($order){
        $message = new \stdClass();

        $message->recipients = [$order->customerPhone];
        $message->params = [
            ['key' => 'ORDERID', 'value' => $order->number],
            ['key' => 'ACTUALAMOUNT', 'value' => $order->actualAmount],
        ];

        /*return $this->send([
            'action'    =>  'message',
            'data'      =>  $message
        ], self::MESSAGE_CANT_CALL_ID.'/send');*/
    }

    /**
     * @param $order History
     *
     * @return int|string
     */
    public function sendPayed($order){
        $message = new \stdClass();

        $message->recipients = [$order->customerPhone];
        $message->params = [
            ['key' => 'ORDERID', 'value' => $order->number],
            ['key' => 'ACTUALAMOUNT', 'value' => $order->actualAmount],
        ];

        return $this->send([
            'action'    =>  'message',
            'data'      =>  $message
        ], self::MESSAGE_PAYMENT_CONFIRMED_ID.'/send');
    }

    /**
     * @param $order History
     *
     * @return int|string
     */
    public function sendWaitDelivery($order){
        $message = new \stdClass();

        $message->recipients = [$order->customerPhone];
        $message->params = [
            ['key' => 'ORDERID', 'value' => $order->number],
            ['key' => 'ACTUALAMOUNT', 'value' => $order->actualAmount],
        ];

        return $this->send([
            'action'    =>  'message',
            'data'      =>  $message
        ], self::MESSAGE_ORDER_DONE_ID.'/send');
    }

    /**
     * @param $order History
     *
     * @return int|string
     */
    public function sendDelivered($order){
        $message = new \stdClass();

        $message->recipients = [$order->customerPhone];
        $message->params = [
            ['key' => 'ORDERID', 'value' => $order->number],
            ['key' => 'ACTUALAMOUNT', 'value' => $order->actualAmount],
        ];

        /*return $this->send([
            'action'    =>  'message',
            'data'      =>  $message
        ], self::MESSAGE_CANT_CALL_ID.'/send');*/
    }

    /**
     * @param $order History
     *
     * @return int|string
     */
    public function sendWaitPayment($order){
        $message = new \stdClass();

        $message->recipients = [$order->customerPhone];
        $message->params = [
            ['key' => 'ORDERID', 'value' => $order->number],
            ['key' => 'ACTUALAMOUNT', 'value' => $order->actualAmount],
            ['key' => 'BANKNAME', 'value' => $order->paymentParam],
            ['key' => 'CARDNUMBER', 'value' => $order->paymentParam],
        ];

        /*return $this->send([
            'action'    =>  'message',
            'data'      =>  $message
        ], self::MESSAGE_CANT_CALL_ID.'/send');*/
    }

}