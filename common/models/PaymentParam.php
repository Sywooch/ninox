<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "paymentParams".
 *
 * @property integer $id
 * @property string $description
 * @property string $value
 * @property string $options
 * @property integer $enabled
 */
class PaymentParam extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'paymentParams';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['enabled'], 'integer'],
            [['description', 'value', 'options'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('shop', 'ID'),
            'description' => Yii::t('shop', 'Description'),
            'value' => Yii::t('shop', 'Value'),
            'options' => Yii::t('shop', 'Options'),
            'enabled' => Yii::t('shop', 'Enabled'),
        ];
    }
}
