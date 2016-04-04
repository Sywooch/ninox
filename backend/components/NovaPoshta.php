<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 23.07.15
 * Time: 12:44
 */

namespace backend\components;


use linslin\yii2\curl\Curl;
use yii\base\Component;
use yii\helpers\Json;

class NovaPoshta extends Component{

    private $url = 'http://testapi.novaposhta.ua/v2.0/';
    private $format = 'json';
    private $apiKey = '5fdddf77cb55decfcbe289063799c67e';
    private $cities;

    public $serviceTypes;
    public $paymentMethods;

    /**
     * @param string|array $method - метод
     * @param array $addons - добавочные куски
     *
     * @return string
     */
    public function getUrl($method, $addons = []){
        if(is_array($method)){
            $method = implode('/', $method);
        }

        if(empty($addons)){
            $addons = '';
        }elseif(is_array($addons)){
            $addons = implode('/', $addons).'/';
        }

        return $this->url.$method.'/'.$this->format.'/'.$addons;
    }

    public function paymentMethods(){
        if(\Yii::$app->cache->exists('novaPoshta/paymentMethods')){
            $this->paymentMethods = \Yii::$app->cache->get('novaPoshta/paymentMethods');
            return $this->paymentMethods;
        }

        $request = [
            'modelName'     =>  'Common',
            'calledMethod'  =>  'getPaymentForms',
            'language'      =>  'ru'
        ];

        $request = $this->sendRequest($request, ['common', 'getPaymentForms'])->response;

        if(empty($request)){
            return [];
        }

        $request = Json::decode($request);

        $types = [];

        foreach($request['data'] as $part){
            $types[$part['Ref']] = $part['Description'];
        }

        \Yii::$app->cache->set('novaPoshta/paymentMethods', $types, 86400);
        \Yii::trace('added NovaPoshta paymentMethods to cache');

        $this->paymentMethods = $types;

        return $types;
    }

    private function sendRequest($request, $method, $addons = []){
        $request['apiKey'] = $this->apiKey;
        $request = Json::encode($request);

        $result = new Curl();

        \Yii::trace('Sending request...');
        \Yii::trace($request);

        $result
            ->setOption(CURLINFO_CONTENT_TYPE, 'application/json')
            ->setOption(CURLOPT_POSTFIELDS, $request)
            ->post($this->getUrl($method, $addons));

        \Yii::trace('Obtaining result...');
        \Yii::trace(Json::encode($result));

        return $result;

    }

    public function city($city, $area = null){
        $response = Json::decode($this->sendRequest([
            'modelName'         =>  'Address',
            'calledMethod'      =>  'getCities',
            'methodProperties'  =>  [
                'FindByString'  =>  $city
            ]
        ], 'address/getCities')->response);

        $response = $response['data'];

        \Yii::trace($response[0]['Ref'], __METHOD__);

        if(empty($response)){
            return false;
        }

        return $response['0']['Ref'];

        if(sizeof($response) >= 1 && $area != null){
            foreach($response as $city){
                if($city['Area'] == $area){
                    \Yii::trace($city, __METHOD__);
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
        ], 'address', ['getWarehouses'])->response);

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
        $request = $this->sendRequest([
            'modelName'         =>  'InternetDocument',
            'calledMethod'      =>  'save',
            'methodProperties'  =>  $order
        ], 'en/save');

        $response = Json::decode($request->response);

        if($response['success'] != 1){
            foreach($response['errors'] as $error){
                $attribute = '';

                switch($error){
                    case 'Counterparty for Payment NonCash is invalid':
                        $attribute = 'PaymentMethod';
                        break;
                    case 'SeatsAmount empty':
                        $attribute = 'SeatsAmount';
                        break;
                    case 'Cost is empty':
                        $attribute = 'Cost';
                        break;
                    case 'Description empty':
                        $attribute = 'Description';
                        break;

                }

                if(!empty($attribute)){
                    $order->addError($attribute, \Yii::t('NovaPoshtaErrors', $error));
                }
            }
        }else{
            $order->orderData->nakladna = $response['data']['0']['IntDocNumber'];
            $order->orderData->save();
        }


        return $order;
    }

    public function createRecipient($recipient){
        $response = Json::decode($this->sendRequest([
            'modelName'         =>  'Counterparty',
            'calledMethod'      =>  'save',
            'methodProperties'  =>  $recipient
        ], 'counterparty', ['save'])->response);

        \Yii::trace($response, __METHOD__);

        return isset($response['data']) && isset($response['data']['0']) ? $response['data']['0'] : false;
    }

    public function createRecipientContact($contact){
        $response = Json::decode($this->sendRequest([
            'modelName'         =>  'ContactPerson',
            'calledMethod'      =>  'save',
            'methodProperties'  =>  $contact
        ], 'counterparty', ['ContactPerson', 'save'])->response);

        return isset($response['data']) && isset($response['data']['0']) ? $response['data']['0'] : false;
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

        $request = $this->sendRequest($request, 'common/getServiceTypes')->response;

        if(empty($request)){
            return [];
        }

        $request = Json::decode($request);

        $types = [];

        foreach($request['data'] as $part){
            $types[$part['Ref']] = $part['Description'];
        }

        \Yii::$app->cache->set('novaPoshta/serviceTypes', $types, 86400);
        \Yii::trace('added NovaPoshta serviceTypes to cache');

        $this->serviceTypes = $types;

        return $types;
    }

}