<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "paymentTypes".
 *
 * @property integer $id
 * @property string $description
 * @property integer $enabled
 * @property integer $modifyLabel
 */
class PaymentType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'paymentTypes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['enabled', 'modifyLabel'], 'integer'],
            [['description'], 'string', 'max' => 255],
        ];
    }

    public function getParams(){
        return PaymentParam::find()->where(['in', 'id', DomainDeliveryPayment::find()->select('paymentParam')->where(['paymentType' => $this->id])]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('shop', 'ID'),
            'description' => Yii::t('shop', 'Description'),
            'enabled' => Yii::t('shop', 'Enabled'),
            'modifyLabel' => Yii::t('shop', 'Modify Label'),
        ];
    }
}
