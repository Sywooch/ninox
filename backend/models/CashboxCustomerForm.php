<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 28.12.15
 * Time: 17:56
 */

namespace backend\models;


use yii\base\Model;

class CashboxCustomerForm extends Model{

    public $name;
    public $surname;
    public $city;
    public $region;
    public $phone;
    public $email;
    public $cardNumber;

    public $id;

    public function save(){
        //Функция сохранения формы
    }

    public function attributeLabels(){
        return [
            'name'      =>  'Имя',
            'surname'   =>  'Фамилия',
            'city'      =>  'Город',
            'region'    =>  'Область',
            'phone'     =>  'Телефон',
            'email'     =>  'email',
            'cardNumber'=>  'Номер карты'
        ];
    }

}