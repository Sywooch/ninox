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
 * @property History[] $notPayedOrders
 * @property History firstOrder
 * @property History lastOrder
 * @property float spentMoney
 * @property History[] $actualOrders
 */

class Customer extends \common\models\Customer{

    public function getActualOrders(){
        $actualOrders = [];

        foreach($this->orders as $order){
            if($order->deleted == 0){
                $actualOrders[] = $order;
            }
        }

        return $actualOrders;
    }

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

    public function getOrdersPeriod(){
        if($this->firstOrder->added == $this->lastOrder->added || count($this->orders) == 0){
            return 0;
        }

        return round((time() - $this->firstOrder->added) / count($this->orders));
    }

    public function getMiddleOrder(){
        if(count($this->actualOrders) == 0){
            return 0;
        }

        return round($this->spentMoney / count($this->actualOrders), 2);
    }

    public function getSpentMoney(){
        $ordersSum = 0;

        foreach($this->actualOrders as $order){
            $ordersSum += $order->actualAmount;
        }

        return $ordersSum;
    }

    public function getFirstOrder(){
        $firstOrder = new History;

        foreach($this->actualOrders as $order){
            if($firstOrder->isNewRecord || $firstOrder->added > $order->added){
                $firstOrder = $order;
            }
        }

        return $firstOrder;
    }

    public function getLastOrder(){
        $lastOrder = new History;

        foreach($this->actualOrders as $order){
            if($lastOrder->isNewRecord || $lastOrder->added < $order->added){
                $lastOrder = $order;
            }
        }

        return $lastOrder;
    }
}