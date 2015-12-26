<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cashboxOrders".
 *
 * @property string $id
 * @property string $customerID
 * @property integer $responsibleUser
 * @property string $createdTime
 * @property string $doneTime
 * @property integer $priceType
 * @property integer $deleted
 */
class CashboxOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cashboxOrders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'customerID', 'responsibleUser', 'priceType', 'deleted'], 'integer'],
            [['createdTime', 'doneTime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'customerID' => Yii::t('common', 'Customer ID'),
            'responsibleUser' => Yii::t('common', 'Responsible User'),
            'createdTime' => Yii::t('common', 'Created Time'),
            'doneTime' => Yii::t('common', 'Done Time'),
            'priceType' => Yii::t('common', 'Price Type'),
            'deleted' => Yii::t('common', 'Deleted'),
        ];
    }

    public function beforeSave($insert){
        if($this->isNewRecord){
            $this->id = hexdec(uniqid());
        }

        return parent::beforeSave($insert);
    }
}
