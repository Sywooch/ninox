<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "problems".
 *
 * @property integer $id
 * @property integer $orderNumber
 * @property string $phone
 * @property string $text
 * @property string $received
 * @property integer $read
 * @property string $email
 * @property integer $type
 */
class Problem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'problems';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orderNumber', 'read', 'type'], 'integer'],
            [['text', 'received'], 'required'],
            [['text', 'email'], 'string'],
            [['received'], 'safe'],
            [['phone'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'orderNumber' => Yii::t('backend', 'Order Number'),
            'phone' => Yii::t('backend', 'Phone'),
            'text' => Yii::t('backend', 'Проблема'),
            'received' => Yii::t('backend', 'Received'),
            'read' => Yii::t('backend', 'Read'),
            'email' => Yii::t('backend', 'Email'),
            'type' => Yii::t('backend', 'Type'),
        ];
    }
}
