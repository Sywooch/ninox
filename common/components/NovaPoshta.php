<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 23.07.15
 * Time: 12:44
 */

namespace common\components;


use yii\base\Component;

class NovaPoshta extends Component{

    private $url = 'https://api.novaposhta.ua/v2.0/json/';
    private $apiKey = '1898fef2714a3b954d05eba062715493';

    private function sendRequest($request){



    }

    public function createOrder($order){
        $order->apiKey = $this->apiKey;
    }


}