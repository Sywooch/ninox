<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 11.12.15
 * Time: 14:17
 */

namespace frontend\models;


class History extends \common\models\History{

    public function getID(){
        return $this->id;
    }

    public function setID($val){
        $this->id = $val;
    }

    public function getItems($returnAll = true){
        return $this->hasMany(SborkaItem::className(), ['orderID' => 'ID']);
    }

    public function rules()
    {
        return [
            [['id', 'number', 'added', 'deliveryType', 'customerID', 'paymentType', 'callback', 'canChangeItems', 'actualAmount', 'moneyCollectorUserId', 'globalmoney', 'nakladnaSendState', 'done', 'responsibleUserID', 'confirmed', 'moneyConfirmed', 'confirm_otd', 'processed', 'smsState', 'deleted', 'takeOrder', 'takeTTNMoney', 'boxesCount', 'isNew', 'transactionSended', 'callsCount', 'hasChanges', 'receiverID'], 'integer'],
            [['customerComment'], 'string'],
            [['amountDeductedOrder', 'originalSum'], 'number'],
            [['moneyConfirmedDate', 'doneDate', 'sendDate', 'receivedDate', 'takeOrderDate', 'takeTTNMoneyDate', 'deleteDate', 'confirmedDate', 'smsSendDate', 'nakladnaSendDate'], 'safe'],
            [['customerEmail', 'deliveryAddress', 'deliveryRegion', 'deliveryCity', 'deliveryInfo', 'coupon'], 'string', 'max' => 255],
            [['customerName', 'customerSurname', 'customerPhone', 'customerFathername'], 'string', 'max' => 64],
            [['nakladna'], 'string', 'max' => 50],
        ];
    }

}