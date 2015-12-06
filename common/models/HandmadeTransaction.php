<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "handmade_transactions".
 *
 * @property integer $id
 * @property string $customerID
 * @property double $summ
 * @property string $type
 * @property string $description
 * @property string $date
 */
class HandmadeTransaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'handmade_transactions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customerID'], 'integer'],
            [['summ', 'type', 'description', 'date'], 'required'],
            [['summ'], 'number'],
            [['type'], 'string'],
            [['date'], 'safe'],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'customerID' => Yii::t('common', 'Customer ID'),
            'summ' => Yii::t('common', 'Summ'),
            'type' => Yii::t('common', 'Type'),
            'description' => Yii::t('common', 'Description'),
            'date' => Yii::t('common', 'Date'),
        ];
    }
}
