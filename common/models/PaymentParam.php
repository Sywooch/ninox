<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "paymentParams".
 *
 * @property integer $id
 * @property string $description
 * @property string $value
 * @property string $option
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
            [['description', 'value', 'option'], 'string', 'max' => 255],
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
            'option' => Yii::t('shop', 'Option'),
            'enabled' => Yii::t('shop', 'Enabled'),
        ];
    }
}
