<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "shopsGoodsTransferringInvoices".
 *
 * @property integer $id
 * @property integer $shopFrom
 * @property integer $shopTo
 * @property string $sendDate
 * @property string $receiveDate
 * @property integer $sender
 * @property integer $receiver
 */
class ShopGoodTransferringInvoice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shopsGoodsTransferringInvoices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'shopFrom', 'shopTo', 'sender'], 'required'],
            [['id', 'shopFrom', 'shopTo', 'sender', 'receiver'], 'integer'],
            [['sendDate', 'receiveDate'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'shopFrom' => Yii::t('common', 'Shop From'),
            'shopTo' => Yii::t('common', 'Shop To'),
            'sendDate' => Yii::t('common', 'Send Date'),
            'receiveDate' => Yii::t('common', 'Receive Date'),
            'sender' => Yii::t('common', 'Sender'),
            'receiver' => Yii::t('common', 'Receiver'),
        ];
    }
}
