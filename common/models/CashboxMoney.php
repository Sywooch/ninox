<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cashboxMoney".
 *
 * @property integer $ID
 * @property integer $cashbox
 * @property integer $operation
 * @property double $amount
 * @property string $date
 * @property integer $order
 * @property integer $customer
 * @property integer $responsibleUser
 */
class CashboxMoney extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cashboxMoney';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cashbox', 'operation', 'order', 'customer', 'responsibleUser'], 'integer'],
            [['amount'], 'number'],
            [['date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('common', 'ID'),
            'cashbox' => Yii::t('common', 'Cashbox'),
            'operation' => Yii::t('common', 'Operation'),
            'amount' => Yii::t('common', 'Amount'),
            'date' => Yii::t('common', 'Date'),
            'order' => Yii::t('common', 'Order'),
            'customer' => Yii::t('common', 'Customer'),
            'responsibleUser' => Yii::t('common', 'Responsible User'),
        ];
    }
}