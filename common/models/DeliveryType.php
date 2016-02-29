<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "deliveryTypes".
 *
 * @property integer $id
 * @property string $description
 * @property integer $replaceDescription
 * @property integer $enabled
 */
class DeliveryType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'deliveryTypes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['replaceDescription', 'enabled'], 'integer'],
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
            'replaceDescription' => Yii::t('shop', 'Replace Description'),
            'enabled' => Yii::t('shop', 'Enabled'),
        ];
    }
}
