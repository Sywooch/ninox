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

    private function sendRequest($request){
        $request['apiKey'] = $this->apiKey;
        $request = Json::encode($request);

        $result = new Curl();

        $result->setOption(CURLOPT_POSTFIELDS, $request)->post($this->url);

        return $result;

    }

    public function cityCode($city){
        if(\Yii::$app->cache->exists('novaPoshta/cities')){
            $this->cities = \Yii::$app->cache->get('novaPoshta/cities');
        }



        return $this->cities;
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

    public function createOrder($order){
        return $this->sendRequest($order);
    }


}