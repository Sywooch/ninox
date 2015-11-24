<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "callback".
 *
 * @property integer $id
 * @property string $phone
 * @property string $question
 * @property string $received
 * @property integer $did_callback
 * @property string $customerName
 * @property integer $deleted
 */
class Callback extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'callback';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone', 'question', 'received', 'customerName'], 'required'],
            [['received'], 'safe'],
            [['did_callback', 'deleted'], 'integer'],
            [['phone'], 'string', 'max' => 20],
            [['question'], 'string', 'max' => 1000],
            [['customerName'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'phone' => Yii::t('backend', 'Phone'),
            'question' => Yii::t('backend', 'Question'),
            'received' => Yii::t('backend', 'Received'),
            'did_callback' => Yii::t('backend', 'Did Callback'),
            'customerName' => Yii::t('backend', 'Customer Name'),
            'deleted' => Yii::t('backend', 'Deleted'),
        ];
    }
}
