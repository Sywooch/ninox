<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 16.12.15
 * Time: 15:42
 */

namespace backend\models;


/**
 * Class Customer
 * @package backend\models
 * @property History $notPayedOrders
 */

class Customer extends \common\models\Customer{

    public function init(){
        $this->discount = !empty($this->discount) ? $this->discount : 0;

        return parent::init();
    }

    public function getReturns(){
        return [];
        //return $this->hasMany()
    }

    public function getNotPayedOrders(){
        return $this->hasMany(History::className(), ['customerID' => 'ID'])->andWhere(['moneyConfirmed' => 0, 'deleted' => 0]);
    }

    public function getDiscount(){
        return !empty($this->cardNumber) ? 2 : 0;
    }
}