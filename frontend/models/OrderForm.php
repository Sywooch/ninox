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

class OrderForm extends Model{

    public $customerID;
    public $customerName;
    public $customerSurname;
    public $customerFathername;
    public $customerEmail;
    public $customerPhone;
    public $customerComment;

    public $deliveryCity;
    public $deliveryRegion;
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
        //затем подхватываем контакт партнёра
        //если у партнёра нет контактов - создаём новый
        //если есть - выбираем один,
    }


}