<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 26.04.16
 * Time: 17:55
 */

namespace frontend\models;


use yii\base\Model;

class SubscribeForm extends Model
{

    public $email;

    public function rules()
    {
        return [
            ['email', 'required']
        ];
    }

    public function subscribe()
    {
        if(!$this->validate()){
            return $this->getErrors();
        }

        return \Yii::$app->email->subscribeCustomer($this->email);
    }

}