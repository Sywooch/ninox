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
    const OPERATION_SELL = 0;
    const OPERATION_REFUND = 1;
    const OPERATION_TAKE = 2;
    const OPERATION_PUT = 3;
    const OPERATION_SELF_DELIVERY = 4;
    const OPERATION_SPEND = 5;


    public function getTypes(){
        return [
            self::OPERATION_SELL            =>  'Покупка',
            self::OPERATION_TAKE            =>  'Забранно',
            self::OPERATION_PUT             =>  'Добавлено',
            self::OPERATION_SELF_DELIVERY   =>  'Самовывоз',
            self::OPERATION_SPEND           =>  'Траты',
            self::OPERATION_REFUND          =>  'Возврат',
        ];
    }

    public function getOrderModel(){
        return $this->hasOne(History::className(), ['id' => 'order']);
    }

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
            [['operation'], 'required'],
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

    public function beforeSave($insert)
    {
        if($this->isNewRecord){
            $this->setAttributes([
                'date'              =>  date('Y-m-d H:i:s')
            ]);
        }

        if(empty($this->responsibleUser)){
            $this->responsibleUser = \Yii::$app->user->id;
        }

        if(empty($this->customer)){
            $this->customer = 0;
        }

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}
