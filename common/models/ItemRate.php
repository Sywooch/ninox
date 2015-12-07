<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "items_rate".
 *
 * @property integer $itemID
 * @property integer $ip
 * @property string $customerID
 * @property integer $rate
 * @property string $date
 */
class ItemRate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'items_rate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['itemID', 'ip', 'customerID', 'date'], 'required'],
            [['itemID', 'ip', 'customerID', 'rate'], 'integer'],
            [['date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'itemID' => Yii::t('common', 'Item ID'),
            'ip' => Yii::t('common', 'Ip'),
            'customerID' => Yii::t('common', 'Customer ID'),
            'rate' => Yii::t('common', 'Rate'),
            'date' => Yii::t('common', 'Date'),
        ];
    }
}
