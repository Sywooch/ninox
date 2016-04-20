<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 27.07.15
 * Time: 15:57
 */

namespace common\helpers;

use app\models\History;
use app\models\SborkaItem;
use yii\helpers\Json;
use linslin\yii2\curl;

class EsputnikAPI {

    private static $username = 'stylekrasota@gmail.com';
    private static $password = 'f1W080be#0';
    private static $email = '';
    private static $baseURL = 'https://esputnik.com.ua/api/v1';

    public static function _sendRequest($data, $post = 1, $operation = null){
        $url = self::$baseURL."/".$data['action'];

        if(!empty($operation)){
            $url .= "/".$operation;
        }

        $ch = new curl\Curl();

        $ch->setOption(CURLOPT_HEADER, 1)
            ->setOption(CURLOPT_POST, $post)
            ->setOption(CURLOPT_HEADER, 1)
            ->setOption(CURLOPT_HTTPHEADER, ['Accept: application/json', 'Content-Type: application/json;charset=UTF-8'])
            ->setOption(CURLOPT_URL, $url)
            ->setOption(CURLOPT_USERPWD, self::$username.':'.self::$password)
            ->setOption(CURLOPT_RETURNTRANSFER, 1)
            ->setOption(CURLOPT_SSL_VERIFYPEER, false);

        if(isset($data['data'])){
            $ch->setOption(CURLOPT_POSTFIELDS, Json::encode($data['data']));
        }

        var_dump($ch);

        $ch->post($url);

        var_dump($ch);

        if($ch->responseCode){
            return $ch->responseCode;
        }

        return $ch->response;
    }

    public static function orderMail(){
       $order = new \stdClass();

        $orderData = History::findOne(['id' => '14747']);
        $orderItems = SborkaItem::findAll(['orderID' => $orderData->id]);

        // ОБЯЗАТЕЛЬНЫЕ ПОЛЯ
        $order->status = "DELIVERED";
        $order->date = date('Y-m-d').'T'.date('H:i:s');  // Дата заказа в формате yyyy-MM-ddTHH:mm:ss.
        $order->externalOrderId = $orderData->id;  // Идентификатор заказа в Вашей системе.
        $order->externalCustomerId = $orderData->customerID;  // Идентификатор клиента в Вашей системе. Если вы ходите идентифицировать клиентов по email или номеру телефона, продублируйте значение в этом поле и в соответствующем поле email или phone.
        $order->totalCost = $orderData->originalSum;  // Итоговая сумма по заказу.

        // НЕОБЯЗАТЕЛЬНЫЕ ПОЛЯ

        $order->email = $orderData->customerEmail;  // Email клиента.
        $order->phone = $orderData->customerPhone;  // Номер телефона клиента.
        //$order->storeId = "1050";  // Для ситуации, если Вам нужно хранить несколько наборов данных (по разным магазинам) в одной учетной записи eSputnik, иначе можно оставить пустым.
        //$order->shipping = 1;  // Стоимость доставки (дополнительная информация, при расчётах не учитывается).
        //$order->taxes = 20;  // Налоги (дополнительная информация, при расчётах не учитывается).
        //$order->discount = 10;  // Скидка (дополнительная информация, при расчётах не учитывается).
        //$order->restoreUrl = "http://test.com?restore";  // Cсылка на восстановление корзины, если необходима такая функциональность.
        //$order->statusDescriptsion = "test";  // Дополнительное описание статуса заказа.

        foreach($orderItems as $item){
            $order->items[] = [
                'name'              =>  $item->name,
                'cost'              =>  $item->price,
                'category'          =>  'cat',
                'quantity'          =>  $item->count,
                'externalItemId'    =>  $item->id,
                'imageUrl'          =>  ''
            ];
        }

        $orders_list = new \stdClass();
        $orders_list->orders = array($order);

        return self::_sendRequest([
            'action'    =>  'orders',
            'data'      =>  $orders_list
        ]);
    }



}