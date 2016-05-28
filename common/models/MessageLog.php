<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "messageslog".
 *
 * @property integer $id
 * @property integer $orderID
 * @property integer $messageID
 * @property string $changed
 */
class MessageLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'messageslog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orderID', 'messageID'], 'required'],
            [['orderID', 'messageID'], 'integer'],
            [['changed'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orderID' => 'Order ID',
            'messageID' => 'Message ID',
            'changed' => 'Changed',
        ];
    }
}
