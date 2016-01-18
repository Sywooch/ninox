<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "shopsGoodsTransferringItems".
 *
 * @property integer $invoiceID
 * @property integer $itemID
 * @property integer $count
 * @property integer $received
 */
class ShopGoodTransferringItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shopsGoodsTransferringItems';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoiceID', 'itemID', 'count'], 'required'],
            [['invoiceID', 'itemID', 'count', 'received'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invoiceID' => Yii::t('common', 'Invoice ID'),
            'itemID' => Yii::t('common', 'Item ID'),
            'count' => Yii::t('common', 'Count'),
            'received' => Yii::t('common', 'Received'),
        ];
    }
}
