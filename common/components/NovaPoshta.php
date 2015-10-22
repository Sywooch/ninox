<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 23.07.15
 * Time: 12:44
 */

namespace common\components;


use linslin\yii2\curl\Curl;
use yii\base\Component;
use yii\helpers\Json;

class NovaPoshta extends Component{

    private $url = 'https://api.novaposhta.ua/v2.0/json/';
    private $apiKey = '1898fef2714a3b954d05eba062715493';
    private $cities;

    public $serviceTypes;

    public function senders(){

    }

    private function sendRequest($request){
        $request['apiKey'] = $this->apiKey;
        $request = Json::encode($request);

        $result = new Curl();

        $result->setOption(CURLOPT_POSTFIELDS, $request)->post($this->url);

        return $result;

    }

    public function city($city, $area = null){
        $response = Json::decode($this->sendRequest([
            'modelName'         =>  'Address',
            'calledMethod'      =>  'getCities',
            'methodProperties'  =>  [
                'FindByString'  =>  $city
            ]
        ])->response);

        $response = $response['data'];

        if(empty($response)){
            return false;
        }

        if(sizeof($response) >= 1 && $area != null){
            foreach($response as $city){
                if($city['Area'] == $area){
                    return $city;
                }
            }
        }

        return $response['0'];
    }

    public function departments($city){
        $response = Json::decode($this->sendRequest([
            'modelName'     =>  'AddressGeneral',
            'calledMethod'  =>  'getWarehouses',
            'methodProperties'  =>  [
                'CityRef'   =>  $city
            ]
        ])->response);

        if(empty($response)){
            return false;
        }

        return $response;
    }

    public function department($number, $city){
        $response = $this->departments($city);

        if(!$response){
            return false;
        }

        foreach($response['data'] as $item){
            if($item['Number'] == $number){
                return $item;
            }
        }

        return false;
    }

    public function createOrder($order){
        return $this->sendRequest([
            'modelName'         =>  'InternetDocument',
            'calledMethod'      =>  'save',
            'methodProperties'  =>  $order
        ]);
    }

    public function createRecipient($recipient){
        $response = Json::decode($this->sendRequest([
            'modelName'         =>  'Counterparty',
            'calledMethod'      =>  'save',
            'methodProperties'  =>  $recipient
        ])->response);

        return $response['data']['0'];
    }

    public function createRecipientContact($contact){

    }

    public function serviceTypes(){
        if(\Yii::$app->cache->exists('novaPoshta/serviceTypes')){
            $this->serviceTypes = \Yii::$app->cache->get('novaPoshta/serviceTypes');
            return $this->serviceTypes;
        }

        $request = [
            'modelName'     =>  'Common',
            'calledMethod'  =>  'getServiceTypes',
            'language'      =>  'ru'
        ];

        $request = $this->sendRequest($request)->response;

        if(empty($request)){
            return [];
        }

        $request = Json::decode($request);

        $types = [];

        foreach($request['data'] as $part){
            $types[$part['Ref']] = $part['Description'];
        }

        \Yii::$app->cache->set('novaPoshta/serviceTypes', $types, 86400);
        \Yii::trace('added to cache');

        $this->serviceTypes = $types;

        return $types;
    }

}