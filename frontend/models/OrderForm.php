<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 09.12.15
 * Time: 14:50
 */

namespace frontend\models;


use common\models\SborkaItem;
use yii\base\ErrorException;
use yii\base\Model;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

class OrderForm extends Model{

    /**
     * поля модели Customer
     */
    public $customerID;
    public $customerName;
    public $customerSurname;
    public $customerFathername;
    public $customerEmail;
    public $customerPhone;

    /**
     * поля модели CustomerReceiver
     */
    public $customerReceiverID = 0;
    public $customerReceiverIsDefault = 0;
    public $deliveryCountry = 'Украина';
    public $deliveryCity;
    public $deliveryRegion;
    public $deliveryAddress;

    public $deliveryType = 1;
    public $deliveryInfo;

    public $paymentType;
    public $paymentInfo;

    /**
     * @var $customerComment - комментарий клиента к заказу
     * @var $promoCode - промокод к заказу
     * @var $canChangeItems - можно делать замену в заказе
     */
    public $customerComment;
    public $promoCode;
    public $canChangeItems = 0;
    public $anotherReceiver = 0;
    public $payment = 0;

    public function init(){
        /*if(\Yii::$app->user->isGuest){
            $this->customerPhone = \Yii::$app->request->cookies->get("customerPhone");
        }*/

        parent::init();
    }

    public function rules()
    {
        return [
            //[['id', 'nakladna', 'takeOrderDate', 'takeTTNMoneyDate'], 'required'],
            [['customerID', 'customerName', 'customerSurname', 'customerFathername', 'customerEmail', 'customerPhone', 'deliveryCountry', 'deliveryCity', 'deliveryRegion', 'deliveryAddress', 'deliveryType', 'deliveryInfo', 'paymentType', 'paymentInfo', 'customerComment', 'promoCode', 'canChangeItems'], 'safe'],
            [['customerName', 'customerSurname', 'customerFathername', 'deliveryCity', 'deliveryRegion', 'deliveryAddress', 'deliveryInfo'], 'string'],
            [['customerName', 'customerSurname', 'customerEmail', 'deliveryCity', 'deliveryRegion', 'deliveryType', 'deliveryInfo'], 'required']
            //[['customerComment'], 'string'],
            //[['amountDeductedOrder', 'originalSum'], 'number'],
            //[['moneyConfirmedDate', 'doneDate', 'sendDate', 'receivedDate', 'takeOrderDate', 'takeTTNMoneyDate', 'deleteDate', 'confirmedDate', 'smsSendDate', 'nakladnaSendDate'], 'safe'],
            //[['customerEmail', 'deliveryAddress', 'deliveryRegion', 'deliveryCity', 'deliveryInfo', 'coupon', 'paymentInfo'], 'string', 'max' => 255],
            //[['customerName', 'customerSurname', 'customerPhone', 'customerFathername'], 'string', 'max' => 64],
            //[['nakladna'], 'string', 'max' => 50],
        ];
    }

    public function attributeLabels(){
        return [
            'customerName'      =>  \Yii::t('shop', 'Имя'),
            'customerSurname'   =>  \Yii::t('shop', 'Фамилия'),
            'customerPhone'     =>  \Yii::t('shop', 'Телефон'),
            'customerEmail'     =>  \Yii::t('shop', 'Эл. почта'),
            'deliveryCity'      =>  \Yii::t('shop', 'Город'),
        ];
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

    public function loadCustomerReceiver($receiver){
        if($receiver instanceof CustomerReceiver == false){
            throw new ErrorException("Может быть передан только CustomerReceiver!");
        }

        $this->customerSurname = $receiver->surname;
        $this->customerFathername = $receiver->fathername;
        $this->deliveryCountry = $receiver->country;
        $this->deliveryCity = $receiver->city;
        $this->deliveryAddress = $receiver->address;
        $this->deliveryRegion = $receiver->region;
        $this->deliveryType = $receiver->shippingType;
        $this->deliveryInfo = $receiver->shippingParam;
        $this->paymentType = $receiver->paymentType;
        $this->paymentInfo = $receiver->paymentParam;
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
                'name' => $this->customerName,
                'surname' => $this->customerSurname,
                'fathername' => $this->customerFathername,
                'partnerID' => $customer->ID,
                'country' => $this->deliveryCountry,
                'city' => $this->deliveryCity,
                'region' => $this->deliveryRegion,
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
            $customerReceiver->name = $this->customerName;
            $customerReceiver->fathername = $this->customerFathername;
            $customerReceiver->country = $this->deliveryCountry;
            $customerReceiver->city = $this->deliveryCity;
            $customerReceiver->address = $this->deliveryAddress;
            $customerReceiver->region = $this->deliveryRegion;
            $customerReceiver->shippingType = $this->deliveryType;
            $customerReceiver->shippingParam = $this->deliveryInfo;
            $customerReceiver->paymentType = $this->paymentType;
            $customerReceiver->paymentParam = $this->paymentInfo;
        }

        $customerReceiver->save();
        //затем подхватываем контакт партнёра
        //если у партнёра нет контактов - создаём новый
        //если есть - выбираем один,
        //затем, создаём модель заказа

        $order = new History([
            'customerEmail'     =>  $customer->email,       //TODO: $customerReceiver->email?
            'customerName'      =>  $customerReceiver->name,
            'customerSurname'   =>  $customerReceiver->surname,
            'customerPhone'     =>  $customer->phone,       //TODO: $customerReceiver->phone?,
            'deliveryAddress'   =>  $customerReceiver->address,
            'deliveryRegion'    =>  $customerReceiver->region,
            'customerFathername'=>  $customerReceiver->fathername,
            'deliveryCity'      =>  $customerReceiver->city,
            'deliveryType'      =>  $customerReceiver->shippingType,
            'deliveryInfo'      =>  $customerReceiver->shippingParam,
            'customerComment'   =>  $this->customerComment,
            'customerID'        =>  $customer->ID,
            'coupon'            =>  $this->promoCode,
            'paymentType'       =>  $customerReceiver->paymentType,
            'paymentInfo'       =>  $customerReceiver->paymentParam,
            'canChangeItems'    =>  $this->canChangeItems,
            'originalSum'       =>  \Yii::$app->cart->cartRealSumm,
        ]);

        if($order->save()){
            foreach(\Yii::$app->cart->goods as $good){
                $orderItem = new SborkaItem([
                    'orderID'       =>  $order->id,
                    'itemID'        =>  $good->ID,
                    'name'          =>  $good->Name,
                    'count'         =>  \Yii::$app->cart->has($good->ID),
                    'originalPrice' =>  \Yii::$app->cart->isWholesale() ? $good->wholesale_price : $good->retail_price,
                    'discountSize'  =>  $good->discountSize,
                    'discountType'  =>  $good->discountType,
                    'priceRuleID'   =>  $good->priceRuleID,
                    'category'      =>  $good->category,
                    'customerRule'  =>  $good->personalRule,
                ]);
                if($orderItem->save()){
                    \Yii::$app->cart->remove($good->ID);
                }
            }

            return true;
        }

        $this->addError('order', Json::encode($order->getErrors()));

        return false;
    }


}