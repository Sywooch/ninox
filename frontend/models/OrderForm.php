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
use yii\helpers\Json;

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
     * поля для другого получателя (пока что есть, но не вставляются)
     */
    public $anotherReceiverName;
    public $anotherReceiverSurname;
    public $anotherReceiverPhone;

    /**
     * поля модели CustomerReceiver
     */
    public $customerReceiverID = 0;
    public $customerReceiverIsDefault = 0;
    public $deliveryCountry = 'Украина';
    public $deliveryCity;
    public $deliveryRegion;
    public $deliveryAddress;

    public $deliveryType = 2;
    public $deliveryParam = 1;
    public $_deliveryInfo = '';

    public $paymentType = 1;
    public $paymentParam = 0;
    public $paymentInfo = '';

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

    /**
     * @type integer - ID заказа после оформления
     */
    public $createdOrder = null;

    /**
     * @type integer - ID клиента, который оформил заказ на другого человека
     */
    public $orderProvider = null;

    public function init(){
        if(\Yii::$app->user->isGuest){
            if(!empty(\Yii::$app->request->post("phone"))){
                $this->customerPhone = preg_replace('/\D+/', '', \Yii::$app->request->post("phone"));
            }elseif(!empty(\Yii::$app->request->cookies->getValue("customerPhone"))){
                $this->customerPhone = \Yii::$app->request->cookies->getValue("customerPhone");
            }
        }

        parent::init();
    }

    public function getDeliveryInfo(){
        return $this->_deliveryInfo;
    }

    public function setDeliveryInfo($val){
        $this->_deliveryInfo = isset($val[$this->deliveryType]) && isset($val[$this->deliveryType][$this->deliveryParam]) ?
            $val[$this->deliveryType][$this->deliveryParam] : '';
    }

    public function rules()
    {
        return [
            //[['id', 'nakladna', 'takeOrderDate', 'takeTTNMoneyDate'], 'required'],
            [['anotherReceiver', 'anotherReceiverName', 'anotherReceiverSurname', 'anotherReceiverPhone'], 'safe'],
            [['deliveryParam', 'paymentParam'], 'integer'],
            [['customerID', 'customerName', 'customerSurname', 'customerFathername', 'customerEmail', 'customerPhone', 'deliveryCountry', 'deliveryCity', 'deliveryRegion', 'deliveryAddress', 'deliveryType', 'deliveryInfo', 'paymentType', 'paymentInfo', 'customerComment', 'promoCode', 'canChangeItems'], 'safe'],
            [['customerName', 'customerSurname', 'customerEmail', 'customerFathername', 'deliveryCity', 'deliveryRegion', 'deliveryAddress', 'deliveryInfo', 'customerComment'], 'string'],
            [['customerName', 'customerSurname', 'deliveryCity', 'deliveryRegion', 'deliveryType'], 'required'],
            ['deliveryInfo', 'required',
                'when' => function(){
                    return in_array($this->deliveryType, [1, 2]);
                },
                'whenClient' => "function(attribute, value){
                    return $(attribute.input).parents('.tab-pane.active').length > 1;
                }"
            ],
            [['anotherReceiverName', 'anotherReceiverSurname', 'anotherReceiverPhone'], 'required',
                'when' => function(){
                    return $this->anotherReceiver != 0;
                },
                'whenClient' => "function(attribute, value){
                    return $(attribute.input).parents('.tab-pane.active').length;
                }"
            ],
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
            'customerName'              =>  \Yii::t('shop', 'Имя'),
            'customerSurname'           =>  \Yii::t('shop', 'Фамилия'),
            'customerPhone'             =>  \Yii::t('shop', 'Телефон'),
            'customerEmail'             =>  \Yii::t('shop', 'Эл. почта'),
            'deliveryCity'              =>  \Yii::t('shop', 'Город'),
            'deliveryRegion'            =>  \Yii::t('shop', 'Область'),
            'deliveryInfo'              =>  \Yii::t('shop', 'Данные о доставке'),
            'anotherReceiverName'       =>  \Yii::t('shop', 'Имя'),
            'anotherReceiverSurname'    =>  \Yii::t('shop', 'Фамилия'),
            'anotherReceiverPhone'      =>  \Yii::t('shop', 'Телефон'),
            'customerComment'           =>  \Yii::t('shop', 'Добавить коментарий к заказу'),
        ];
    }

    public function getRegions(){
        $order = new \common\models\History();

        return $order->regions;
    }

    public function getAvailableReceivers(){
        return [
            '0' =>  \Yii::t('shop', 'Отправлять на меня'),
            '1' =>  \Yii::t('shop', 'Будет получать другой человек'),
        ];
    }

    /**
     * Загружает данные о клиенте в модель формы заказа
     *
     * @param $customer Customer
     *
     * @throws \yii\base\ErrorException
     */
    public function loadCustomer($customer){
        if($customer instanceof Customer == false){
            throw new ErrorException("Может быть передан только Customer!");
        }

        $this->setAttributes([
            'customerID'        =>  $customer->ID,
            'customerName'      =>  $customer->name,
            'customerSurname'   =>  $customer->surname,
            'customerPhone'     =>  $customer->phone,
            'customerEmail'     =>  $customer->email,
            'deliveryCity'      =>  $customer->city,
            'paymentType'       =>  $customer->paymentType,
            'paymentParam'      =>  $customer->paymentParam,
            'paymentInfo'       =>  $customer->paymentInfo
        ]);

        if(isset($this->getRegions()[$customer->region])){
            $this->deliveryRegion = $customer->region;
        }
    }

    /**
     * @param $receiver
     * @deprecated
     *
     * @throws \yii\base\ErrorException
     */
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
        $this->deliveryParam = $receiver->shippingParam;
        $this->paymentType = $receiver->paymentType;
        $this->paymentParam = $receiver->paymentParam;
    }

    /**
     * @return bool оформлен-ли заказ
     */
    public function create(){
        if($this->anotherReceiver == 1){
            $this->setAttributes([
                'customerName'      =>  $this->anotherReceiverName,
                'customerSurname'   =>  $this->anotherReceiverSurname
            ]);

            if(!empty($this->anotherReceiverPhone)){
                $this->setAttributes([
                    'orderProvider' =>  Customer::find()->select('ID')->where(['phone' => $this->customerPhone])->scalar(),
                    'customerPhone' =>  $this->anotherReceiverPhone
                ]);
            }
        }

        //Пользователь залогинен
        if(!\Yii::$app->user->isGuest && $this->anotherReceiver == 0){
            $customer = \Yii::$app->user->identity;
        }

        //Пользователь не залогинен, ищем по номеру телефона
        if(empty($customer)){
            $customer = Customer::findOne(['phone' => $this->customerPhone]);
        }

        //Нет пользователя с таким номером - добавляем нового, и логиним его
        if(empty($customer)){
            $customer = new User([
                'name'      =>  $this->customerName,
                'surname'   =>  $this->customerSurname,
                'phone'     =>  $this->customerPhone,
                'email'     =>  $this->customerEmail
            ]);

            $customer->save(false);

            \Yii::$app->user->login($customer, 3600*24*30);
        }

        $order = new History([
            'customerEmail'     =>  $this->customerEmail,
            'customerName'      =>  $this->customerName,
            'customerSurname'   =>  $this->customerSurname,
            'customerPhone'     =>  $this->customerPhone,
            'deliveryAddress'   =>  $this->deliveryInfo,
            'deliveryRegion'    =>  $this->deliveryRegion,
            'customerFathername'=>  $this->customerFathername,
            'deliveryCity'      =>  $this->deliveryCity,
            'deliveryType'      =>  $this->deliveryType,
            'deliveryParam'     =>  $this->deliveryParam,
            'deliveryInfo'      =>  $this->deliveryInfo,
            'customerComment'   =>  $this->customerComment,
            'customerID'        =>  $customer->ID,
            'coupon'            =>  $this->promoCode,
            'paymentType'       =>  $this->paymentType,
            'paymentParam'      =>  $this->paymentParam,
            'canChangeItems'    =>  $this->canChangeItems,
            'originalSum'       =>  \Yii::$app->cart->cartRealSumm,
        ]);

        if(!empty($this->orderProvider)){
            $order->orderProvider = $this->orderProvider;
        }

        if(\Yii::$app->request->post("orderType") == 1){
            $order->sourceInfo = History::SOURCEINFO_ONECLICK;
        }

        if($order->save()){
            $this->createdOrder = $order->id;

            $orderSuperRealPrice = 0;

            foreach(\Yii::$app->cart->goods as $good){
                if(!empty($customer->cardNumber) && $good->discountSize == 0){
                    $good->setAttributes([
                        'discountSize'  =>  2,
                        'discountType'  =>  2,
                    ], false);

                    $good->customerRule = -1;
                }

                $orderItem = new SborkaItem([
                    'orderID'       =>  $order->id,
                    'itemID'        =>  $good->ID,
                    'name'          =>  $good->Name,
                    'count'         =>  \Yii::$app->cart->has($good->ID),
                    'originalPrice' =>  \Yii::$app->cart->wholesale ? $good->realWholesalePrice : $good->realRetailPrice,
                    'discountSize'  =>  $good->discountSize,
                    'discountType'  =>  $good->discountType,
                    'priceRuleID'   =>  $good->priceRuleID,
                    'categoryCode'  =>  $good->categoryCode,
                    'customerRule'  =>  $good->customerRule,
                    'storeID'       =>  1
                ]);

                $orderSuperRealPrice += ($orderItem->price * $orderItem->count);

                if($orderItem->save(false)){
                    \Yii::$app->cart->remove($good->ID, false);
                }
            }

            /* TODO: чистой воды костыль */
            $order->originalSum = $orderSuperRealPrice;
            $order->save(false);

            \Yii::$app->cart->recalcCart();
            \Yii::$app->cart->save();

            $customer->setAttributes([
                'name'          =>  $this->customerName,
                'surname'       =>  $this->customerSurname,
                'city'          =>  $this->deliveryCity,
                'region'        =>  $this->deliveryRegion,
                'deliveryType'  =>  $this->deliveryType,
                'deliveryInfo'  =>  $this->deliveryInfo,
                'deliveryParam' =>  $this->deliveryParam,
                'paymentType'   =>  $this->paymentType,
                'paymentParam'  =>  $this->paymentParam,
                'paymentInfo'   =>  $this->paymentInfo,
            ]);

            $customer->save(false);

            return true;
        }

        $this->addError('order', Json::encode($order->getErrors()));

        return false;
    }


}