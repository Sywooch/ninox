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

        return $this->send([
            'action'    =>  'message',
            'data'      =>  $message
        ], self::MESSAGE_CANT_CALL_ID.'/send');
    }

}