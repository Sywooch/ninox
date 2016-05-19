<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 04.05.16
 * Time: 14:30
 */

namespace frontend\models;


use yii\base\Model;

class ReviewForm extends Model
{

    public $name;

    public $city;

    public $review;

    public $customerType;

    public $question;

    public function rules(){
        if(\Yii::$app->user->isGuest) {
        return [
            [['name', 'city', 'review'], 'required'],
            [['name', 'city', 'customerType'], 'string', 'max' => 255],
            [['review'], 'string'],
            [['question'], 'boolean']
        ];}
        else{
            return [
                [['review'], 'required'],
                [['name', 'city', 'customerType'], 'string', 'max' => 255],
                [['review'], 'string'],
                [['question'], 'boolean']
            ];
        }
    }

    public function getCustomerTypes(){
        return [
            'Предприниматель'       =>  \Yii::t('shop', 'Предприниматель'),
            'Оптовый покупатель'    =>  \Yii::t('shop', 'Оптовый покупатель'),
            'Покупаю для себя'      =>  \Yii::t('shop', 'Покупаю для себя'),
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'          =>  \Yii::t('shop', 'Имя и фамилия'),
            'city'          =>  \Yii::t('shop', 'Город'),
            'review'        =>  \Yii::t('shop', 'Отзыв'),
            'customerType'  =>  \Yii::t('shop', 'Кто вы?'),
            'question'      =>  \Yii::t('shop', 'Довольны ли вы нашим сервисом?'),
        ];
    }

    public function save(){
        if(!$this->validate()){
            return $this->validate();
        }

        $review = new Review([
            'name'          =>  $this->name,
            'city'          =>  $this->city,
            'customerType'  =>  $this->customerType,
            'review'        =>  $this->review,
            'question1'     =>  $this->question,
        ]);

        return $review->save(false);
    }

}