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
            [['phone', 'cardNumber'], 'integer'],
            [['name', 'surname'], 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\backend\models\Customer', 'message' => 'Пользователь с таким аддресом электронной почты уже существует!'],
            ['phone', 'unique', 'targetClass' => '\backend\models\Customer', 'message' => 'Пользователь с таким номером телефона уже существует!'],
        ];
    }

    public function save(){
        $customer = new Customer();

        foreach($this->modelAttributes() as $newAttribute => $oldAttribute){
            if(is_bool($this->$newAttribute)){
                $customer->$oldAttribute = $this->$newAttribute ? 1 : 0;
            }else{
                $customer->$oldAttribute = $this->$newAttribute;
            }
        }

        if($this->validate() && $customer->save(false)){
            $this->id = $customer->ID;

            return true;
        }else{
            foreach($this->modelAttributes() as $newAttribute => $oldAttribute){
                $customer->getErrors($oldAttribute) ? $this->addError($newAttribute, $customer->getErrors($oldAttribute)[0]) : false;
            }
        }


        return false;
    }

    public function modelAttributes(){
        return [
            'name'          =>  'name',
            'surname'       =>  'surname',
            'city'          =>  'city',
            'region'        =>  'region',
            'phone'         =>  'phone',
            'email'         =>  'email',
            'cardNumber'    =>  'cardNumber'

        ];
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