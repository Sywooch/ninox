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
 * @property integer $domainId
 * @property integer $deleted
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
            [['published', 'domainId', 'deleted'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'name' => Yii::t('backend', 'Name'),
            'photo' => Yii::t('backend', 'Photo'),
            'question' => Yii::t('backend', 'Question'),
            'answer' => Yii::t('backend', 'Answer'),
            'date_question' => Yii::t('backend', 'Date Question'),
            'date_answer' => Yii::t('backend', 'Date Answer'),
            'published' => Yii::t('backend', 'Published'),
            'email' => Yii::t('backend', 'Email'),
            'phone' => Yii::t('backend', 'Phone'),
            'domainId' => Yii::t('backend', 'Domain ID'),
            'deleted' => Yii::t('backend', 'Deleted'),
        ];
    }
}
