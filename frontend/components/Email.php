<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 29.01.16
 * Time: 17:56
 */

namespace frontend\components;


use frontend\models\Customer;
use frontend\models\History;
use yii\bootstrap\Html;
use yii\helpers\Url;

class Email extends \common\components\Email{

    const PASSWORD_RESET_MESSAGE = '445326';

    private $defaultSubscribeGroup = '340381';

    /**
     * @param $order History
     *
     * @return int|string
     */
    public function orderEmail($order){
        $emailOrder = new \stdClass();

        $emailOrder->status = 'DELIVERED';
        $emailOrder->date = date('Y-m-d').'T'.date('H:i:s');
        $emailOrder->externalOrderId = $order->number;
        $emailOrder->externalCustomerId = empty($order->customerID) ? '0' : $order->customerID;
        $emailOrder->firstName = $order->customerName;
        $emailOrder->lastName = $order->customerSurname;
        $emailOrder->totalCost = $order->originalSum;
        $emailOrder->email = $order->customerEmail;
        $emailOrder->phone = $order->customerPhone;

        foreach($order->items as $item){
            $emailOrder->items[] = [
                'name'              =>  $item->name,
                'cost'              =>  $item->price,
                'category'          =>  $item->category->name,
                'quantity'          =>  $item->count,
                'externalItemId'    =>  $item->good->Code,
                'url'               =>  'https://krasota-style.com.ua/tovar/-g'.$item->itemID,
                'imageUrl'          =>  'https:'.\Yii::$app->params['cdn-link'].'/img/catalog/sm/'.$item->photo,
            ];
        }

        $orders = new \stdClass();
        $orders->orders = [$emailOrder];

        return $this->send([
            'action'    =>  'orders',
            'data'      =>  $orders
        ]);

        /*// ОБЯЗАТЕЛЬНЫЕ ПОЛЯ
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
        //$order->statusDescriptsion = "test";  // Дополнительное описание статуса заказа.*/
    }

    /**
     * @param Customer $customer
     * @return int|string
     */
    public function resetPasswordEmail($customer){
        $passwordEmail = new \stdClass();

        $passwordEmail->params = [
            [
                'key'   =>  'FIRSTNAME',
                'value' =>  $customer->name,
            ],
            [
                'key'   =>  'LASTNAME',
                'value' =>  $customer->surname,
            ],
            [
                'key'   =>  'LINK',
                'value' =>  Html::a('ссылке.', \Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $customer->password_reset_token]), [
                    'style'     =>  'color: #02A7D4; font-size: 14px; font-weight: bold',
                    'target'    =>  '_blank'
                ])
            ],
        ];

        $passwordEmail->recipients = [$customer->email];

        return $this->send([
            'action'    =>  'message/'.self::PASSWORD_RESET_MESSAGE.'/send',
            'data'      =>  $passwordEmail
        ]);
    }
    
    /**
     * Подписывает клиента на нас :)
     *
     * @param $email string
     * @return int|string
     */
    public function subscribeCustomer($email){
        $contact = new \stdClass();
        $contactJson = new \stdClass();

        $contact->channels = ['type' => 'email', 'value' => $email];
        //$contact->addressBookId = $this->defaultSubscribeGroup;

        $contactJson->contact = $contact;
        $contactJson->groups = ['Подписчики'];

        return $this->send([
            'action'    =>  'contact/subscribe',
            'data'      =>  $contactJson
        ]);
    }

}