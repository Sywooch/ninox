<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 28.12.15
 * Time: 17:56
 */

namespace cashbox\models;


use backend\models\Customer;
use yii\base\Model;

class CustomerForm extends Model{

    public $name;
    public $surname;
    public $city;
    public $region;
    public $phone;
    public $email;
    public $cardNumber;

    public $id;

    public function rules(){
        return [
            [['name', 'surname', 'city', 'region'], 'string'],
            [['phone', 'cardNumber', 'cardNumber'], 'integer'],
            [['name', 'surname', 'phone'], 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\backend\models\Customer', 'message' => 'Пользователь с таким аддресом электронной почты уже существует!'],
            ['phone', 'unique', 'targetClass' => '\backend\models\Customer', 'message' => 'Пользователь с таким номером телефона уже существует!'],
        ];
    }

    public function save(){
        if(!$this->validate()){
            return false;
        }

        $customer = new Customer();
        $customer->name = $this->name;
        $customer->surname = $this->surname;
        $customer->City = $this->city.', '.$this->region;
        $customer->phone = $this->phone;
        $customer->email = $this->email;
        $customer->cardNumber = $this->cardNumber;

        if($customer->save()){
            $this->id = $customer->ID;

            return true;
        }

        return false;
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