<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "goodscomments".
 *
 * @property integer $goodID
 * @property integer $commentID
 * @property integer $target
 * @property integer $type
 * @property string $who
 * @property string $customerID
 * @property string $email
 * @property string $what
 * @property string $date
 * @property integer $show
 * @property integer $moderate
 */
class GoodsComment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goodscomments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goodID', 'who', 'what'], 'required'],
            [['goodID', 'target', 'type', 'customerID', 'show', 'moderate'], 'integer'],
            [['who', 'email', 'what'], 'string'],
            [['date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'goodID' => Yii::t('common', 'Good ID'),
            'commentID' => Yii::t('common', 'Comment ID'),
            'target' => Yii::t('common', 'Target'),
            'type' => Yii::t('common', 'Type'),
            'who' => Yii::t('common', 'Who'),
            'customerID' => Yii::t('common', 'Customer ID'),
            'email' => Yii::t('common', 'Email'),
            'what' => Yii::t('common', 'What'),
            'date' => Yii::t('common', 'Date'),
            'show' => Yii::t('common', 'Show'),
            'moderate' => Yii::t('common', 'Moderate'),
        ];
    }
}
