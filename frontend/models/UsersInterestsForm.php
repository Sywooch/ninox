<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 23.04.16
 * Time: 16:21
 */

namespace frontend\models;


use common\models\UsersInterests;
use yii\base\Model;


class UsersInterestsForm extends Model
{

    /**
     * @type string
     */
    public $name;

    /**
     * @type string
     */
    public $email;

    /**
     * @type string
     */
    public $text;

    public function save(){
        $UsersInterests = new UsersInterests();

        $UsersInterests->setAttributes([
            'name'          =>  $this->name,
            'email'         =>  $this->email,
            'text'          =>  $this->text,
        ], false);

        $UsersInterests->save(false);
    }

    public function rules()
    {
        return [
            [['name', 'email', 'text'], 'required'],
            [['name', 'email', 'text'], 'string'],
        ];
    }

    public function attributeLabels(){
        return [
            'name'           =>  \Yii::t('shop', 'Ваше имя'),
            'emeil'          =>  \Yii::t('shop', 'Ваш емейл'),
            'text'           =>  \Yii::t('shop', 'Что вас интересует?'),
        ];
    }
}