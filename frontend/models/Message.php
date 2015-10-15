<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "source_message".
 *
 * @property integer $id
 * @property string $category
 * @property string $message
 *
 * @property Message[] $messages
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'source_message';
    }

    public static function getGoodTranslate($goodID){
        $query = new Query();
        $query = $query
            ->select(['a.message as message', 'b.translation as translation'])
            ->from([self::tableName().' a', 'message b'])
            ->where([
                'a.category'    =>  'shop-info-goods',
                'b.language'    =>  \Yii::$app->language,
            ])
            ->andWhere(['like', 'a.message', '%'.$goodID, false])
            ->andWhere('b.id = a.id')
            ->all();

        $result = [];

        foreach($query as $item){
            $result[preg_replace('/-\d+/', '', $item->message)] = $item->translation;
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message'], 'string'],
            [['category'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category' => 'Category',
            'message' => 'Message',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['id' => 'id']);
    }
}
