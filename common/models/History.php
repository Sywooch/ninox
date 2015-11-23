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
 * @property integer $customerID
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
 */
class History extends \yii\db\ActiveRecord
{
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
            [['id', 'number', 'added', 'deliveryType', 'customerID', 'paymentType', 'callback', 'canChangeItems', 'actualAmount', 'moneyCollectorUserId', 'globalmoney', 'nakladnaSendState', 'done', 'responsibleUserID', 'confirmed', 'moneyConfirmed', 'confirm_otd', 'processed', 'smsState', 'deleted', 'takeOrder', 'takeTTNMoney', 'boxesCount', 'isNew', 'transactionSended', 'callsCount', 'hasChanges'], 'integer'],
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
            'id' => Yii::t('backend', 'ID'),
            'number' => Yii::t('backend', 'Number'),
            'customerEmail' => Yii::t('backend', 'Customer Email'),
            'customerName' => Yii::t('backend', 'Customer Name'),
            'customerSurname' => Yii::t('backend', 'Customer Surname'),
            'customerPhone' => Yii::t('backend', 'Customer Phone'),
            'deliveryAddress' => Yii::t('backend', 'Delivery Address'),
            'deliveryRegion' => Yii::t('backend', 'Delivery Region'),
            'added' => Yii::t('backend', 'Added'),
            'customerFathername' => Yii::t('backend', 'Customer Fathername'),
            'deliveryType' => Yii::t('backend', 'Delivery Type'),
            'deliveryCity' => Yii::t('backend', 'Delivery City'),
            'customerComment' => Yii::t('backend', 'Customer Comment'),
            'customerID' => Yii::t('backend', 'Customer ID'),
            'deliveryInfo' => Yii::t('backend', 'Delivery Info'),
            'coupon' => Yii::t('backend', 'Coupon'),
            'paymentType' => Yii::t('backend', 'Payment Type'),
            'paymentInfo' => Yii::t('backend', 'Payment Info'),
            'callback' => Yii::t('backend', 'Callback'),
            'canChangeItems' => Yii::t('backend', 'Can Change Items'),
            'actualAmount' => Yii::t('backend', 'Actual Amount'),
            'amountDeductedOrder' => Yii::t('backend', 'Amount Deducted Order'),
            'moneyCollectorUserId' => Yii::t('backend', 'Money Collector User ID'),
            'nakladna' => Yii::t('backend', 'Nakladna'),
            'globalmoney' => Yii::t('backend', 'Globalmoney'),
            'nakladnaSendState' => Yii::t('backend', 'Nakladna Send State'),
            'done' => Yii::t('backend', 'Done'),
            'responsibleUserID' => Yii::t('backend', 'Responsible User ID'),
            'confirmed' => Yii::t('backend', 'Confirmed'),
            'moneyConfirmed' => Yii::t('backend', 'Money Confirmed'),
            'confirm_otd' => Yii::t('backend', 'Confirm Otd'),
            'moneyConfirmedDate' => Yii::t('backend', 'Money Confirmed Date'),
            'processed' => Yii::t('backend', 'Processed'),
            'doneDate' => Yii::t('backend', 'Done Date'),
            'sendDate' => Yii::t('backend', 'Send Date'),
            'receivedDate' => Yii::t('backend', 'Received Date'),
            'smsState' => Yii::t('backend', 'Sms State'),
            'deleted' => Yii::t('backend', 'Deleted'),
            'takeOrder' => Yii::t('backend', 'Take Order'),
            'takeOrderDate' => Yii::t('backend', 'Take Order Date'),
            'takeTTNMoney' => Yii::t('backend', 'Take Ttnmoney'),
            'takeTTNMoneyDate' => Yii::t('backend', 'Take Ttnmoney Date'),
            'boxesCount' => Yii::t('backend', 'Boxes Count'),
            'isNew' => Yii::t('backend', 'Is New'),
            'deleteDate' => Yii::t('backend', 'Delete Date'),
            'transactionSended' => Yii::t('backend', 'Transaction Sended'),
            'confirmedDate' => Yii::t('backend', 'Confirmed Date'),
            'smsSendDate' => Yii::t('backend', 'Sms Send Date'),
            'callsCount' => Yii::t('backend', 'Calls Count'),
            'originalSum' => Yii::t('backend', 'Original Sum'),
            'nakladnaSendDate' => Yii::t('backend', 'Nakladna Send Date'),
            'hasChanges' => Yii::t('backend', 'Has Changes'),
        ];
    }
}
