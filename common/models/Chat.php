<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "chats".
 *
 * @property integer $id
 * @property integer $creator
 * @property string $name
 * @property string $avatar
 * @property string $timestamp
 */
class Chat extends \yii\db\ActiveRecord
{

    public $messagesCount = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chats';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['creator', 'name', 'avatar'], 'required'],
            [['creator'], 'integer'],
            [['timestamp'], 'safe'],
            [['name', 'avatar'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'creator' => Yii::t('common', 'Creator'),
            'name' => Yii::t('common', 'Name'),
            'avatar' => Yii::t('common', 'Avatar'),
            'timestamp' => Yii::t('common', 'Timestamp'),
        ];
    }
}
