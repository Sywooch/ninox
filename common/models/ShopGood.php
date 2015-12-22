<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "shopsGoods".
 *
 * @property integer $shopID
 * @property integer $itemID
 * @property integer $count
 */
class ShopGood extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shopsGoods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shopID', 'itemID'], 'required'],
            [['shopID', 'itemID', 'count'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'shopID' => Yii::t('common', 'Shop ID'),
            'itemID' => Yii::t('common', 'Item ID'),
            'count' => Yii::t('common', 'Count'),
        ];
    }
}
