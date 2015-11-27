<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "chatReceivers".
 *
 * @property integer $chat
 * @property integer $user
 */
class ChatReceiver extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chatReceivers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['chat', 'user'], 'required'],
            [['chat', 'user'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'chat' => Yii::t('common', 'Chat'),
            'user' => Yii::t('common', 'User'),
        ];
    }
}
