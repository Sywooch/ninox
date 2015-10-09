<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "reviews".
 *
 * @property integer $id
 * @property string $date
 * @property string $name
 * @property string $city
 * @property integer $type
 * @property string $review
 * @property integer $question1
 * @property integer $question2
 * @property integer $published
 * @property string $client_face
 * @property integer $position
 * @property string $customerType
 * @property integer $deleted
 */
class Review extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reviews';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'name'], 'required'],
            [['date'], 'safe'],
            [['name', 'city', 'review', 'client_face', 'customerType'], 'string'],
            [['type', 'question1', 'question2', 'published', 'position', 'deleted'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'name' => 'Name',
            'city' => 'City',
            'type' => 'Type',
            'review' => 'Review',
            'question1' => 'Question1',
            'question2' => 'Question2',
            'published' => 'Published',
            'client_face' => 'Client Face',
            'position' => 'Position',
            'customerType' => 'Customer Type',
            'deleted' => 'Deleted',
        ];
    }

    public static function changeState($id, $field = 'published'){
        $a = Review::findOne(['id' => $id]);

        if ($a) {
            /*
            if ($field == 'published') {
                $a->published = $a->published == "1" ? "0" : "1";
                $a->save(false);
                return $a->published;
            } else {
                $a->deleted = $a->deleted == "1" ? "0" : "1";
                $a->save(false);
                return $a->deleted;
            }*/
            $a->$field = $a->$field == 1 ? 0 : 1;
            $a->save(false);
            return $a->$field;
        }

        return 0;
    }

	public static function getReviews(){
		return self::find()->where(['published' => 1])->
			orderBy('type ASC, IF (type = 1, - UNIX_TIMESTAMP(`date`) , UNIX_TIMESTAMP(`date`)) ASC')->all();
	}
}
