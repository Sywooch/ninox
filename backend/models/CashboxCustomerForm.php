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

    public function add(){

    }

}