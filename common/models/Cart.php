<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cart".
 *
 * @property string $customerID
 * @property string $cartCode
 * @property integer $itemID
 * @property integer $count
 * @property string $date
 */
class Cart extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cart';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customerID', 'cartCode', 'itemID', 'count', 'date'], 'required'],
            [['customerID', 'itemID', 'count'], 'integer'],
            [['date'], 'safe'],
            [['cartCode'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customerID' => Yii::t('common', 'Customer ID'),
            'cartCode' => Yii::t('common', 'Cart Code'),
            'itemID' => Yii::t('common', 'Item ID'),
            'count' => Yii::t('common', 'Count'),
            'date' => Yii::t('common', 'Date'),
        ];
    }
}
