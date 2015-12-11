<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 09.12.15
 * Time: 14:50
 */

namespace frontend\models;


use yii\base\ErrorException;
use yii\base\Model;
use yii\web\NotFoundHttpException;

class OrderForm extends Model{

    public $customerID;
    public $customerName;
    public $customerSurname;
    public $customerFathername;
    public $customerEmail;
    public $customerPhone;
    public $customerComment;
    public $customerReceiverID = 0;
    public $customerReceiverIsDefault = 0;

    public $deliveryCountry = 'Украина';
    public $deliveryCity;
    public $deliveryRegion;
    public $deliveryAddress;
    public $deliveryType;
    public $deliveryInfo;

    public $paymentType;
    public $paymentInfo;

    public $promoCode;

    public $canChangeItems;

    public function init(){
        //$this->customerPhone = \Yii::$app->user->isGuest ? empty($this->customerPhone) ? \Yii::$app->request->cookies->get("customerPhone") : $this->customerPhone : \Yii::$app->user->identity->phone;

        return parent::init();
    }

    public function loadCustomer($customer){
        if($customer instanceof Customer == false){
            throw new ErrorException("Может быть передан только Customer!");
        }

        $customerNameParts = explode(' ', $customer->Company);

        $this->customerID = $customer->ID;
        $this->customerName = $customerNameParts['0'];
        $this->customerSurname = $customerNameParts['1'];
        $this->customerPhone = $customer->phone;
        $this->customerEmail = $customer->email;
    }

    public function create(){
        //Сначала проверяем, гость-ли пользователь
        //если гость - создаём нового партнёра
        if(\Yii::$app->user->isGuest){
            $customer = Customer::findOne(['phone' => $this->customerPhone]);

            if(!$customer){
                $customer = new User();
                //$customer->name
                //$customer->surname
                $customer->Company = $this->customerName.' '.$this->customerSurname;
                $customer->phone = $this->customerPhone;
                $customer->email = $this->customerEmail;

                $customer->save();

                \Yii::$app->user->login($customer, 3600*24*30);
            }
        }else{
            $customer = \Yii::$app->user->identity;
        }

        if(\Yii::$app->user->isGuest || $this->customerReceiverID == 0){
            $customerReceiver = new CustomerReceiver([
                'surname' => $this->customerSurname,
                'fathername' => $this->customerFathername,
                'partnerID' => $customer->ID,
                'country' => $this->deliveryCountry,
                'city' => $this->deliveryCity,
                'address' => $this->deliveryAddress,
                'shippingType' => $this->deliveryType,
                'shippingParam' => $this->deliveryInfo,
                'paymentType' => $this->paymentType,
                'paymentParam' => $this->paymentInfo,
            ]);

            if(\Yii::$app->user->isGuest || $this->customerReceiverIsDefault != 0){
                $customerReceiver->default = 1;
            }
        }else{
            $customerReceiver = CustomerReceiver::findOne(['partnerID' => $customer->ID, 'ID' => $this->customerReceiverID]);

            if(!$customerReceiver){
                throw new NotFoundHttpException("Не смогли найти получателя");
            }

            $customerReceiver->surname = $this->customerSurname;
            $customerReceiver->fathername = $this->customerFathername;
            $customerReceiver->country = $this->deliveryCountry;
            $customerReceiver->city = $this->deliveryCity;
            $customerReceiver->address = $this->deliveryAddress;
            $customerReceiver->shippingType = $this->deliveryType;
            $customerReceiver->shippingParam = $this->deliveryInfo;
            $customerReceiver->paymentType = $this->paymentType;
            $customerReceiver->paymentParam = $this->paymentInfo;
        }

        $customerReceiver->save();
        //затем подхватываем контакт партнёра
        //если у партнёра нет контактов - создаём новый
        //если есть - выбираем один,
    }


}