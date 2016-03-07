<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "orderPayments".
 *
 * @property string $ID
 * @property string $orderID
 * @property integer $type
 * @property integer $param
 * @property string $date
 * @property double $amount
 * @property integer $confirmed
 * @property string $confirmationDate
 * @property integer $responsibleUser
 */
class OrderPayment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orderPayments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID'], 'required'],
            [['ID', 'orderID', 'type', 'param', 'confirmed', 'responsibleUser'], 'integer'],
            [['date', 'confirmationDate'], 'safe'],
            [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'orderID' => 'Order ID',
            'type' => 'Type',
            'param' => 'Param',
            'date' => 'Date',
            'amount' => 'Amount',
            'confirmed' => 'Confirmed',
            'confirmationDate' => 'Confirmation Date',
            'responsibleUser' => 'Responsible User',
        ];
    }
}
