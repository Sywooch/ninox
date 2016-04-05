<?php

namespace common\models;

use Yii;
use yii\base\ErrorException;

/**
 * This is the model class for table "history".
 *
 * @property string $id
 * @property integer $number
 * @property string $customerEmail
 * @property string $customerName
 * @property string $customerSurname
 * @property string $customerPhone
 * @property string $deliveryAddress
 * @property string $deliveryRegion
 * @property integer $added
 * @property string $customerFathername
 * @property integer $deliveryType
 * @property integer $deliveryParam
 * @property string $deliveryInfo
 * @property string $deliveryCity
 * @property string $customerComment
 * @property string $customerID
 * @property string $coupon
 * @property integer $paymentType
 * @property integer $paymentParam
 * @property integer $callback
 * @property integer $canChangeItems
 * @property integer $actualAmount
 * @property double $amountDeductedOrder
 * @property integer $moneyCollectorUserId
 * @property string $nakladna
 * @property integer $globalmoney
 * @property integer $nakladnaSendState
 * @property integer $done
 * @property integer $responsibleUserID
 * @property integer $confirmed
 * @property integer $moneyConfirmed
 * @property integer $confirm_otd
 * @property string $moneyConfirmedDate
 * @property integer $processed
 * @property string $doneDate
 * @property string $sendDate
 * @property string $receivedDate
 * @property integer $smsState
 * @property integer $deleted
 * @property integer $takeOrder
 * @property string $takeOrderDate
 * @property integer $takeTTNMoney
 * @property string $takeTTNMoneyDate
 * @property integer $boxesCount
 * @property integer $isNew
 * @property string $deleteDate
 * @property integer $transactionSended
 * @property integer $domainId
 * @property double $currencyExchange
 * @property string $currencyCode
 * @property string $confirmedDate
 * @property string $smsSendDate
 * @property integer $callsCount
 * @property double $originalSum
 * @property string $nakladnaSendDate
 * @property integer $hasChanges
 * @property string $receiverID
 * @property integer $return
 * @property integer $orderSource
 * @property integer $sourceType
 * @property integer $sourceInfo
 * @property double $deliveryCost
 * @property string $deliveryReference
 * @property string $deliveryEstimatedDate
 */
class History extends \yii\db\ActiveRecord
{

    const CALLBACK_NEW = 0;         //Новый заказ (не звонили)
    const CALLBACK_UNANSWERED = 2;  //Заказ без ответа
    const CALLBACK_COMPLETED = 1;   //Прозвоненый заказ

    const SOURCETYPE_INTERNET = 0;  //Заказ из интернета
    const SOURCETYPE_SHOP = 1;      //Заказ из магазина

    const STATUS_NOT_CALLED = 0;    //Не прозвонен
    const STATUS_PROCESS = 1;       //В обработке
    const STATUS_NOT_PAYED = 2;     //Не оплачен
    const STATUS_WAIT_DELIVERY = 3; //Ожидает доставку
    const STATUS_DELIVERED = 4;     //Отправлен
    const STATUS_DONE = 5;          //Выполнен

    public function beforeSave($insert){
        if($this->isNewRecord){
            $this->id = hexdec(uniqid());
            $this->number = $this->find()->max('number') + 1;
            $this->added = time();
        }

        return parent::beforeSave($insert);
    }

    /**
     * Возвращает идентификатор статуса заказа, в зависимости от того, на какой стадии заказ
     *
     * @return int идентификатор статуса
     */
    public function getStatus(){
        if($this->callback != self::CALLBACK_COMPLETED){
            return self::STATUS_NOT_CALLED;
        }

        if($this->paymentType == 2 && $this->moneyConfirmed != 1){
            return self::STATUS_NOT_PAYED;
        }

        $status = self::STATUS_WAIT_DELIVERY;

        if(!empty(preg_replace('/-|\+|\s+/', '', $this->nakladna))){
            $status = self::STATUS_DELIVERED;
        }

        return $status;
    }

    /*public function getOldStatus(){
        if($this->deleted == '4'){
            return $this->status = '7';
        }

        if($this->callback == 0){
            return $this->status = 1;
        }

        if($this->callback == 2){
            return $this->status = 3;
        }

        if($this->callback == '2'){
            return $this->status = '3';
        }

        if($this->done == '1'){
            if(strlen($this->nakladna) > 1 && $this->nakladnaSendState == 1 && $this->actualAmount != 0){
                if($this->moneyConfirmed == '1'){
                    if($this->paymentType == 2 || $this->paymentType == '3' || $this->paymentType == '4'){
                        return $this->status = '8';
                    }

                    return $this->status = '6';
                }

                return $this->status = '9';
            }elseif($this->paymentType == '2' || $this->paymentType == '3' || $this->paymentType == '4' || $this->paymentType == '5'){
                $this->status = $this->moneyConfirmed == '1' ? '6' : '4';
            }elseif($this->paymentType == '1'){
                $this->status = '10';
            }
        }elseif($this->paymentType == '1' && strlen($this->nakladna) > '1' && $this->nakladnaSendState != '0'){
            $this->status = '4';
        }else{
            $this->status = '2';
        }
    }*/

    public function getItems(){
        return SborkaItem::findAll(['orderID' => $this->id]);
    }

    public function loadCustomer($customer){
        if($customer instanceof Customer == false){
            throw new ErrorException("Передана неверная модель клиента!");
        }

        $nameParts = explode(' ', $customer->Company);

        isset($nameParts[0]) ? $this->customerName = $nameParts[0] : false;
        isset($nameParts[1]) ? $this->customerSurname = $nameParts[1] : false;

        $this->customerEmail = $customer->email;
        $this->customerPhone = $customer->phone;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'deliveryInfo', 'nakladna', 'moneyConfirmedDate', 'takeOrderDate', 'takeTTNMoneyDate'], 'required'],
            [['id', 'number', 'added', 'deliveryType', 'deliveryParam', 'customerID', 'paymentType', 'paymentParam', 'callback', 'canChangeItems', 'actualAmount', 'moneyCollectorUserId', 'globalmoney', 'nakladnaSendState', 'done', 'responsibleUserID', 'confirmed', 'moneyConfirmed', 'confirm_otd', 'processed', 'smsState', 'deleted', 'takeOrder', 'takeTTNMoney', 'boxesCount', 'isNew', 'transactionSended', 'domainId', 'callsCount', 'hasChanges', 'receiverID', 'return', 'orderSource', 'sourceType', 'sourceInfo'], 'integer'],
            [['deliveryInfo', 'customerComment'], 'string'],
            [['amountDeductedOrder', 'currencyExchange', 'originalSum', 'deliveryCost'], 'number'],
            [['moneyConfirmedDate', 'doneDate', 'sendDate', 'receivedDate', 'takeOrderDate', 'takeTTNMoneyDate', 'deleteDate', 'confirmedDate', 'smsSendDate', 'nakladnaSendDate'], 'safe'],
            [['customerEmail', 'deliveryAddress', 'deliveryRegion', 'deliveryCity', 'coupon', 'deliveryReference', 'deliveryEstimatedDate'], 'string', 'max' => 255],
            [['customerName', 'customerSurname', 'customerPhone', 'customerFathername'], 'string', 'max' => 64],
            [['nakladna'], 'string', 'max' => 50],
            [['currencyCode'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => 'Number',
            'customerEmail' => 'Customer Email',
            'customerName' => 'Customer Name',
            'customerSurname' => 'Customer Surname',
            'customerPhone' => 'Customer Phone',
            'deliveryAddress' => 'Delivery Address',
            'deliveryRegion' => 'Delivery Region',
            'added' => 'Added',
            'customerFathername' => 'Customer Fathername',
            'deliveryType' => 'Delivery Type',
            'deliveryParam' => 'Delivery Param',
            'deliveryInfo' => 'Delivery Info',
            'deliveryCity' => 'Delivery City',
            'customerComment' => 'Customer Comment',
            'customerID' => 'Customer ID',
            'coupon' => 'Coupon',
            'paymentType' => 'Payment Type',
            'paymentParam' => 'Payment Param',
            'callback' => 'Callback',
            'canChangeItems' => 'Can Change Items',
            'actualAmount' => 'Actual Amount',
            'amountDeductedOrder' => 'Amount Deducted Order',
            'moneyCollectorUserId' => 'Money Collector User ID',
            'nakladna' => 'Nakladna',
            'globalmoney' => 'Globalmoney',
            'nakladnaSendState' => 'Nakladna Send State',
            'done' => 'Done',
            'responsibleUserID' => 'Responsible User ID',
            'confirmed' => 'Confirmed',
            'moneyConfirmed' => 'Money Confirmed',
            'confirm_otd' => 'Confirm Otd',
            'moneyConfirmedDate' => 'Money Confirmed Date',
            'processed' => 'Processed',
            'doneDate' => 'Done Date',
            'sendDate' => 'Send Date',
            'receivedDate' => 'Received Date',
            'smsState' => 'Sms State',
            'deleted' => 'Deleted',
            'takeOrder' => 'Take Order',
            'takeOrderDate' => 'Take Order Date',
            'takeTTNMoney' => 'Take Ttnmoney',
            'takeTTNMoneyDate' => 'Take Ttnmoney Date',
            'boxesCount' => 'Boxes Count',
            'isNew' => 'Is New',
            'deleteDate' => 'Delete Date',
            'transactionSended' => 'Transaction Sended',
            'domainId' => 'Domain ID',
            'currencyExchange' => 'Currency Exchange',
            'currencyCode' => 'Currency Code',
            'confirmedDate' => 'Confirmed Date',
            'smsSendDate' => 'Sms Send Date',
            'callsCount' => 'Calls Count',
            'originalSum' => 'Original Sum',
            'nakladnaSendDate' => 'Nakladna Send Date',
            'hasChanges' => 'Has Changes',
            'receiverID' => 'Receiver ID',
            'return' => 'Return',
            'orderSource' => 'Order Source',
            'sourceType' => 'Source Type',
            'sourceInfo' => 'Source Info',
            'deliveryCost' => 'Delivery Cost',
            'deliveryReference' => 'Delivery Reference',
            'deliveryEstimatedDate' => 'Delivery Estimated Date',
        ];
    }
}
