<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 25.04.16
 * Time: 16:17
 */

namespace backend\models;

use yii\base\Model;
use yii\db\ActiveQuery;

class OrdersStats extends Model
{

    public $from;
    public $to;

    private $_orders = 0;
    private $_doneOrders = 0;
    private $_notCalled = 0;
    private $_waitDelivery = 0;
    private $_amount = 0;
    private $_actualAmount = 0;

    /**
     * @param array $conditions
     * @param array $ignoredFilters
     * @return ActiveQuery
     */
    private function searchOrders($conditions = [], $ignoredFilters = []){
        return (new HistorySearch())->search(array_merge(\Yii::$app->request->get(), $conditions), true, $ignoredFilters);
    }

    public function getOrders(){
        if(empty($this->_orders)){
            $count = $this->searchOrders([], ['ordersStatus' => true])->count();

            $this->_orders = empty($count) ? 0 : $count;
        }

        return $this->_orders;
    }

    public function getDoneOrders(){
        if(empty($this->_doneOrders)){
            $count = $this->searchOrders(['ordersStatus' => History::STATUS_DONE])->count();

            $this->_doneOrders = empty($count) ? 0 : $count;
        }

        return $this->_doneOrders;
    }

    public function getNotCalled(){
        if(empty($this->_notCalled)){
            $count = $this->searchOrders([], ['ordersStatus' => true])->andWhere(['status' => History::STATUS_NOT_CALLED])->count();

            $this->_notCalled = empty($count) ? 0 : $count;
        }

        return $this->_notCalled;
    }

    public function getWaitDelivery(){
        if(empty($this->_waitDelivery)){
            $count = $this->searchOrders(['ordersStatus' => History::STATUS_WAIT_DELIVERY])->count();

            $this->_waitDelivery = empty($count) ? 0 : $count;
        }

        return $this->_waitDelivery;
    }

    public function getOrdersAmount(){
        if(empty($this->_amount)){
            $count = $this->searchOrders([], ['ordersStatus' => true])->sum('originalSum');

            $this->_amount = empty($count) ? 0 : $count;
        }

        return $this->_amount;
    }

    public function getNew(){

    }

    public function getOrdersActualAmount(){
        if(empty($this->_actualAmount)){
            $count = $this->searchOrders([], ['ordersStatus' => true])->sum('actualAmount');

            $this->_actualAmount = empty($count) ? 0 : $count;
        }

        return $this->_actualAmount;
    }

}