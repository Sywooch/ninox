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
 * @property integer $published
 * @property string $customerType
 * @property integer $target
 * @property integer $deleted
 * @property integer $customerID
 * @property string $customerPhoto
 */
class Review extends \yii\db\ActiveRecord
{
    public static function changeTrashState($id){
        $a = Review::findOne(['id' => $id]);
        if($a){
            $a->deleted = $a->deleted == "1" ? "0" : "1";
            $a->save(false);

            return $a->deleted;
        }

        return false;
    }
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
            [['id', 'date'], 'safe'],
            [['name', 'city', 'review', 'customerType'], 'string'],
            [['type', 'question1', 'published', 'target', 'deleted', 'customerID'], 'integer'],
            [['customerPhoto'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'date' => Yii::t('backend', 'Date'),
            'name' => Yii::t('backend', 'Name'),
            'city' => Yii::t('backend', 'City'),
            'type' => Yii::t('backend', 'Type'),
            'review' => Yii::t('backend', 'Отзыв'),
            'question1' => Yii::t('backend', 'Question1'),
            'published' => Yii::t('backend', 'Published'),
            'customerType' => Yii::t('backend', 'Customer Type'),
            'target' => Yii::t('backend', 'Target'),
            'deleted' => Yii::t('backend', 'Deleted'),
            'customerID' => Yii::t('backend', 'Customer ID'),
            'customerPhoto' => Yii::t('backend', 'Фото пользователя'),
        ];
    }

}
