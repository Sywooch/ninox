<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "deliveryParams".
 *
 * @property integer $id
 * @property string $description
 * @property string $option
 * @property integer $enabled
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
            [['option'], 'string'],
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
            'option' => Yii::t('shop', 'Option'),
            'enabled' => Yii::t('shop', 'Enabled'),
        ];
    }
}
