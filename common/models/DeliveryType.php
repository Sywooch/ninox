<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "deliveryTypes".
 *
 * @property integer $id
 * @property string $description
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
            [['enabled'], 'integer'],
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
        ];
    }
}