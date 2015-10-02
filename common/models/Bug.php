<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "bug".
 *
 * @property string $id
 * @property string $realUrl
 * @property string $userUrl
 * @property string $name
 * @property string $description
 * @property string $sended
 * @property integer $sender
 * @property string $innerRoute
 */
class Bug extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bug';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'realUrl', 'userUrl', 'name', 'description', 'sended', 'sender', 'innerRoute'], 'required'],
            [['realUrl', 'userUrl', 'description'], 'string'],
            [['sended'], 'safe'],
            [['sender'], 'integer'],
            [['id', 'name', 'innerRoute'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'realUrl' => 'Real Url',
            'userUrl' => 'User Url',
            'name' => 'Name',
            'description' => 'Description',
            'sended' => 'Sended',
            'sender' => 'Sender',
            'innerRoute' => 'Inner Route',
        ];
    }
}
