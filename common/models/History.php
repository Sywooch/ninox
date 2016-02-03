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
 * @property string $deliveryCity
 * @property string $customerComment
 * @property string $customerID
 * @property string $deliveryInfo
 * @property string $coupon
 * @property integer $paymentType
 * @property string $paymentInfo
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
 * @property string $confirmedDate
 * @property string $smsSendDate
 * @property integer $callsCount
 * @property double $originalSum
 * @property string $nakladnaSendDate
 * @property integer $hasChanges
 * @property string $receiverID
 */
class History extends \yii\db\ActiveRecord
{

    const CALLBACK_NEW = 0;
    const CALLBACK_UNANSWERED = 1;
    const CALLBACK_COMPLETED = 2;

    public $status;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'history';
    }

    public function beforeSave($insert){
        if($this->isNewRecord){
            $this->id = hexdec(uniqid());
            $this->number = $this->find()->max('number') + 1;
            $this->added = time();
        }

        return parent::beforeSave($insert);
    }

    public function getStatus(){
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

    public function loadRecipient($recipient){

    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'nakladna', 'takeOrderDate', 'takeTTNMoneyDate'], 'required'],
            [['id', 'number', 'added', 'deliveryType', 'customerID', 'paymentType', 'callback', 'canChangeItems', 'actualAmount', 'moneyCollectorUserId', 'globalmoney', 'nakladnaSendState', 'done', 'responsibleUserID', 'confirmed', 'moneyConfirmed', 'confirm_otd', 'processed', 'smsState', 'deleted', 'takeOrder', 'takeTTNMoney', 'boxesCount', 'isNew', 'transactionSended', 'callsCount', 'hasChanges', 'receiverID'], 'integer'],
            [['customerComment'], 'string'],
            [['amountDeductedOrder', 'originalSum'], 'number'],
            [['moneyConfirmedDate', 'doneDate', 'sendDate', 'receivedDate', 'takeOrderDate', 'takeTTNMoneyDate', 'deleteDate', 'confirmedDate', 'smsSendDate', 'nakladnaSendDate'], 'safe'],
            [['customerEmail', 'deliveryAddress', 'deliveryRegion', 'deliveryCity', 'deliveryInfo', 'coupon', 'paymentInfo'], 'string', 'max' => 255],
            [['customerName', 'customerSurname', 'customerPhone', 'customerFathername'], 'string', 'max' => 64],
            [['nakladna'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'number' => Yii::t('common', 'Номер заказа'),
            'customerEmail' => Yii::t('common', 'Email клиента'),
            'customerName' => Yii::t('common', 'Имя клиента'),
            'customerSurname' => Yii::t('common', 'Фамилия клиента'),
            'customerPhone' => Yii::t('common', 'Телефон клиента'),
            'deliveryAddress' => Yii::t('common', 'Аддрес доставки'),
            'deliveryRegion' => Yii::t('common', 'Область доставки'),
            'added' => Yii::t('common', 'Заказ сделан'),
            'customerFathername' => Yii::t('common', 'Отчество клиента'),
            'deliveryType' => Yii::t('common', 'Тип доставки'),
            'deliveryCity' => Yii::t('common', 'Город доставки'),
            'customerComment' => Yii::t('common', 'Комментарий клиента'),
            'customerID' => Yii::t('common', 'ID клиента'),
            'deliveryInfo' => Yii::t('common', 'Информация о доставке'),
            'coupon' => Yii::t('common', 'Промо-код'),
            'paymentType' => Yii::t('common', 'Тип оплаты'),
            'paymentInfo' => Yii::t('common', 'Информация о оплате'),
            'callback' => Yii::t('common', 'Перезвонили-ли клиенту'),
            'canChangeItems' => Yii::t('common', 'Можно делать замену'),
            'actualAmount' => Yii::t('common', 'Фактическая сумма'),
            'amountDeductedOrder' => Yii::t('common', 'Списаные со счёта пользователя деньги'),
            'moneyCollectorUserId' => Yii::t('common', 'Забрал деньги за заказ'),
            'nakladna' => Yii::t('common', 'Номер накладной'),
            'globalmoney' => Yii::t('common', 'если заказ globalmoney'),
            'nakladnaSendState' => Yii::t('common', 'Состояние отправки накладной'),
            'done' => Yii::t('common', 'Выполнен-ли заказ'),
            'responsibleUserID' => Yii::t('common', 'Ответственный за заказ'),
            'confirmed' => Yii::t('common', 'Подтверждение заказа менеждером'),
            'moneyConfirmed' => Yii::t('common', 'Подтверждение получения денег'),
            'confirm_otd' => Yii::t('common', 'Confirm Otd'),
            'moneyConfirmedDate' => Yii::t('common', 'Money Confirmed Date'),
            'processed' => Yii::t('common', 'Processed'),
            'doneDate' => Yii::t('common', 'Дата завершения заказа'),
            'sendDate' => Yii::t('common', 'Дата отправки заказа'),
            'receivedDate' => Yii::t('common', 'Received Date'),
            'smsState' => Yii::t('common', 'Состояние отправки смс'),
            'deleted' => Yii::t('common', 'Удалён'),
            'takeOrder' => Yii::t('common', 'Take Order'),
            'takeOrderDate' => Yii::t('common', 'Take Order Date'),
            'takeTTNMoney' => Yii::t('common', 'Take Ttnmoney'),
            'takeTTNMoneyDate' => Yii::t('common', 'Take Ttnmoney Date'),
            'boxesCount' => Yii::t('common', 'Boxes Count'),
            'isNew' => Yii::t('common', 'Is New'),
            'deleteDate' => Yii::t('common', 'Delete Date'),
            'transactionSended' => Yii::t('common', 'Transaction Sended'),
            'confirmedDate' => Yii::t('common', 'Confirmed Date'),
            'smsSendDate' => Yii::t('common', 'Sms Send Date'),
            'callsCount' => Yii::t('common', 'Calls Count'),
            'originalSum' => Yii::t('common', 'Original Sum'),
            'nakladnaSendDate' => Yii::t('common', 'Nakladna Send Date'),
            'hasChanges' => Yii::t('common', 'Has Changes'),
            'receiverID' => Yii::t('common', 'Receiver ID'),
        ];
    }
}
