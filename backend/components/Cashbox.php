<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 28.12.15
 * Time: 13:51
 */

namespace backend\components;


use yii\base\Component;

class Cashbox extends Component{

    public $orderID;
    public $sum;
    public $toPay;
    public $customer;
    public $responsibleUser;
    public $items;
    public $itemsCount;

    public function addItem($itemID, $count = 1){

    }

    public function removeItem($itemID, $count = 1){

    }

    /*public function clear(){

    }*/

}