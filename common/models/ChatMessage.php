<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "chatMessages".
 *
 * @property integer $id
 * @property integer $author
 * @property integer $chat
 * @property string $timestamp
 * @property string $text
 */
class ChatMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chatMessages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['author', 'chat'], 'required'],
            [['author', 'chat'], 'integer'],
            [['timestamp'], 'safe'],
            [['text'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'author' => Yii::t('common', 'Author'),
            'chat' => Yii::t('common', 'Chat'),
            'timestamp' => Yii::t('common', 'Timestamp'),
            'text' => Yii::t('common', 'Text'),
        ];
    }

    public function beforeSave($i){
        $this->timestamp = date('Y-m-d H:i:s');

        return parent::beforeSave($i);
    }
}
