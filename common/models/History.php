<?php

namespace common\models;

use Yii;

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
    const CALLBACK_COMPLETED = 1;
    const CALLBACK_UNANSWERED = 2;

    public function beforeSave($insert){
        if($this->isNewRecord){
            $this->id = hexdec(uniqid());
            $this->number = $this->find()->max('number') + 1;
            $this->added = time();
        }

        return parent::beforeSave($insert);
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
            'number' => Yii::t('common', 'Number'),
            'customerEmail' => Yii::t('common', 'Customer Email'),
            'customerName' => Yii::t('common', 'Customer Name'),
            'customerSurname' => Yii::t('common', 'Customer Surname'),
            'customerPhone' => Yii::t('common', 'Customer Phone'),
            'deliveryAddress' => Yii::t('common', 'Delivery Address'),
            'deliveryRegion' => Yii::t('common', 'Delivery Region'),
            'added' => Yii::t('common', 'Added'),
            'customerFathername' => Yii::t('common', 'Customer Fathername'),
            'deliveryType' => Yii::t('common', 'Delivery Type'),
            'deliveryCity' => Yii::t('common', 'Delivery City'),
            'customerComment' => Yii::t('common', 'Customer Comment'),
            'customerID' => Yii::t('common', 'Customer ID'),
            'deliveryInfo' => Yii::t('common', 'Delivery Info'),
            'coupon' => Yii::t('common', 'Coupon'),
            'paymentType' => Yii::t('common', 'Payment Type'),
            'paymentInfo' => Yii::t('common', 'Payment Info'),
            'callback' => Yii::t('common', 'Callback'),
            'canChangeItems' => Yii::t('common', 'Can Change Items'),
            'actualAmount' => Yii::t('common', 'Actual Amount'),
            'amountDeductedOrder' => Yii::t('common', 'Amount Deducted Order'),
            'moneyCollectorUserId' => Yii::t('common', 'Money Collector User ID'),
            'nakladna' => Yii::t('common', 'Nakladna'),
            'globalmoney' => Yii::t('common', 'Globalmoney'),
            'nakladnaSendState' => Yii::t('common', 'Nakladna Send State'),
            'done' => Yii::t('common', 'Done'),
            'responsibleUserID' => Yii::t('common', 'Responsible User ID'),
            'confirmed' => Yii::t('common', 'Confirmed'),
            'moneyConfirmed' => Yii::t('common', 'Money Confirmed'),
            'confirm_otd' => Yii::t('common', 'Confirm Otd'),
            'moneyConfirmedDate' => Yii::t('common', 'Money Confirmed Date'),
            'processed' => Yii::t('common', 'Processed'),
            'doneDate' => Yii::t('common', 'Done Date'),
            'sendDate' => Yii::t('common', 'Send Date'),
            'receivedDate' => Yii::t('common', 'Received Date'),
            'smsState' => Yii::t('common', 'Sms State'),
            'deleted' => Yii::t('common', 'Deleted'),
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
