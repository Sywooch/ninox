<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "deliveryTypes".
 *
 * @property integer $id
 * @property string $description
 * @property integer $modifyLabel
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
            [['modifyLabel', 'enabled'], 'integer'],
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
            'modifyLabel' => Yii::t('shop', 'Modify Label'),
            'enabled' => Yii::t('shop', 'Enabled'),
        ];
    }
}
