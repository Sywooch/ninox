<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "deliveryParams".
 *
 * @property integer $id
 * @property string $description
 * @property integer $enabled
 * @property string $options
 */
class DeliveryParam extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'deliveryParams';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['enabled'], 'integer'],
            [['options'], 'required'],
            [['options'], 'string'],
            [['description'], 'string', 'max' => 255],
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
            'enabled' => Yii::t('shop', 'Enabled'),
            'options' => Yii::t('shop', 'Options'),
        ];
    }
}
