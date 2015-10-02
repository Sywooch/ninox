<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "questions".
 *
 * @property integer $id
 * @property string $name
 * @property string $photo
 * @property string $question
 * @property string $answer
 * @property string $date_question
 * @property string $date_answer
 * @property integer $published
 * @property string $email
 * @property string $phone
 */
class Question extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'questions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'photo', 'question', 'answer', 'email', 'phone'], 'string'],
            [['date_question', 'date_answer'], 'safe'],
            [['published'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'photo' => 'Photo',
            'question' => 'Question',
            'answer' => 'Answer',
            'date_question' => 'Date Question',
            'date_answer' => 'Date Answer',
            'published' => 'Published',
            'email' => 'Email',
            'phone' => 'Phone',
        ];
    }

	public static function getQuestions()
	{
		return self::find()->where(['published' => 1])->
			andWhere(['domainId' => 1])->all(); //TODO: айди домена нужно подставлять динамически.
	}
}