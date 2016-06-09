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

    private $url = 'https://api.novaposhta.ua/v2.0/';
    private $format = 'json';
    private $apiKey = '5fdddf77cb55decfcbe289063799c67e';
    private $cities;

    public $serviceTypes;
    public $paymentMethods;

    /**
     * @param string|array $method - метод
     * @param string|array $addons - добавочные куски
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

        return $this->url.$this->format.'/'.$method.'/'.$addons;
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

        $this->paymentMethods = $types;

        return $types;
    }

    private function sendRequest($request, $method, array $addons = []){
        $request['apiKey'] = $this->apiKey;
        $request = Json::encode($request);

        $result = new Curl();
        $result
            ->setOption(CURLINFO_CONTENT_TYPE, 'application/json')
            ->setOption(CURLOPT_POSTFIELDS, $request)
            ->post($this->getUrl($method, $addons));

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

        if(empty($response)){
            return false;
        }

        return $response['0']['Ref'];
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
            $order->setAttributes([
                'number'                =>  $response['data']['0']['IntDocNumber'],
                'deliveryCost'          =>  $response['data']['0']['CostOnSite'],
                'deliveryReference'     =>  $response['data']['0']['Ref'],
                'deliveryEstimatedDate' =>  $response['data']['0']['EstimatedDeliveryDate'],
            ]);
        }


        return $order;
    }

    public function createRecipient($recipient){
        $response = Json::decode($this->sendRequest([
            'modelName'         =>  'Counterparty',
            'calledMethod'      =>  'save',
            'methodProperties'  =>  $recipient
        ], 'counterparty', ['save'])->response);

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

    /**
     * @return string[]
     */
    public function typesOfPayers(){
        return [
            'Sender'        =>  'Отправитель',
            'Recipient'     =>  'Получатель',
            'ThirdPerson'   =>  'Третее лицо'
        ];
    }

    public function cargoTypes(){
        if(!\Yii::$app->cache->exists('novaPoshta/cargoTypes')){
            $response = $this
                ->sendRequest(['modelName' =>  'Common', 'calledMethod' => 'getCargoTypes', 'language' => 'ru'], ['common', 'getCargoTypes'])
                ->response;
            $response = Json::decode($response);

            if($response['success']){
                foreach($response['data'] as $cargoDescription){
                    $data[$cargoDescription['Ref']] = $cargoDescription['Description'];
                }

                \Yii::$app->cache->set('novaPoshta/cargoTypes', $data, 3600);
            }
        }

        return \Yii::$app->cache->get('novaPoshta/cargoTypes');
    }

    public function cargoDescriptionList(){
        if(!\Yii::$app->cache->exists('novaPoshta/cargoDescriptionList')){
            $response = $this
                ->sendRequest(['modelName' =>  'Common', 'calledMethod' => 'getCargoDescriptionList', 'language' => 'ru'], ['common', 'getCargoDescriptionList'])
                ->response;

            $response = Json::decode($response);

            if($response['success']){
                foreach($response['data'] as $cargoDescription){
                    $data[$cargoDescription['Ref']] = $cargoDescription['DescriptionRu'];
                }

                \Yii::$app->cache->set('novaPoshta/cargoDescriptionList', $data, 3600);
            }
        }

        return \Yii::$app->cache->get('novaPoshta/cargoDescriptionList');
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

        $this->serviceTypes = $types;

        return $types;
    }

}