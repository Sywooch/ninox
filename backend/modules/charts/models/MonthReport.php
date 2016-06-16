<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 09.06.16
 * Time: 17:38
 */

namespace backend\modules\charts\models;


use backend\models\History;
use backend\models\Shop;
use libphonenumber\ValidationResult;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\widgets\Pjax;

/**
 * @property Shop shop
 * @property History[] orders
 * @property History[] doneOrders
 * @property double planProgress
 * @property integer planProgressInPercents
 * @property History[] todayOrders
 * @property History[] todayInternetOrders
 * @property History[] todayShopOrders
 * @property History[] todayDoneInternetOrders
 * @property History[] todayDoneShopOrders
 */
class MonthReport extends Model
{

    private $_orders = [];
    public $month = '';
    public $year = '';
    private $_shop;

    public function setShop($shop){
        if($shop instanceof Shop == false){
            if(is_int($shop)){
                $shop = Shop::findOne($shop);
            }else{
                return false;
            }
        }

        $this->month = $shop->month;
        $this->year = $shop->year;

        return $this->_shop = $shop;
    }

    public function getShop(){
        return $this->_shop;
    }

    public function init()
    {
        $this->month = date('m');
        $this->year = date('Y');

        parent::init(); // TODO: Change the autogenerated stub
    }


    public function getOrders(){
        if(empty($this->_orders)){
            $dateFrom = strtotime($this->year.'-'.$this->month.'-01');

            $search = new HistorySearch([
                'dateFrom'  =>  $dateFrom,
                'dateTo'    =>  strtotime($this->year.'-'.$this->month.'-'.date('t', $dateFrom))
            ]);

            $this->_orders = $search->search(\Yii::$app->request->get())->andWhere(['orderSource' => $this->shop->id])->all();
        }

        return $this->_orders;
    }

    public function getDoneOrders(){
        $doneOrders = [];

        foreach($this->orders as $order){
            if($order->done){
                $doneOrders[] = $order;
            }
        }

        return $doneOrders;
    }

    public function getTodayDoneOrders(){
        $orders = [];

        foreach($this->todayOrders as $order){
            if($order->done == 1){
                $orders[] = $order;
            }
        }

        return $orders;
    }

    public function getTodayOrders(){
        $orders = [];

        foreach($this->orders as $order){
            if($order->added >= strtotime(date('Y-m-d'))){
                $orders[] = $order;
            }
        }

        return $orders;
    }

    public function getTodayShopOrders(){
        $orders = [];

        foreach($this->todayOrders as $order){
            if($order->sourceType == History::SOURCETYPE_SHOP){
                $orders[] = $order;
            }
        }

        return $orders;
    }

    public function getTodayDoneShopOrders(){
        $orders = [];

        foreach($this->todayShopOrders as $order){
            if($order->done == 1){
                $orders[] = $order;
            }
        }

        return $orders;

    }

    public function getTodayInternetOrders(){
        $orders = [];

        foreach($this->todayOrders as $order){
            if($order->sourceType == History::SOURCETYPE_INTERNET){
                $orders[] = $order;
            }
        }

        return $orders;
    }

    public function getTodayDoneInternetOrders(){
        $orders = [];

        foreach($this->todayInternetOrders as $order){
            if($order->moneyConfirmed == 1){
                $orders[] = $order;
            }
        }

        return $orders;
    }

    public function getTodayInternetOrdersSum(){
        $sum = 0;

        foreach ($this->todayDoneInternetOrders as $order){
            $sum += $order->actualAmount;
        }

        return $sum;
    }

    public function getTodayInternetEarnedSum(){
        $sum = 0;

        foreach ($this->todayDoneInternetOrders as $order){
            $sum += $order->originalSum;
        }

        return $sum;
    }

    public function getTodayShopEarnedSum(){
        $sum = 0;

        foreach ($this->todayDoneShopOrders as $order){
            $sum += $order->originalSum;
        }

        return $sum;
    }

    public function getTodayShopOrdersSum(){
        $sum = 0;

        foreach ($this->todayDoneShopOrders as $order){
            $sum += $order->actualAmount;
        }

        return $sum;
    }

    public function getPlanProgress(){
        $progress = 0;

        foreach ($this->doneOrders as $order){
            $progress += $order->actualAmount;
        }

        return $progress;
    }

    public function getPlanProgressInPercents(){
        $progress = round($this->planProgress / $this->shop->monthPlan * 100, 2);

        if($progress > 100){
            $progress = 100;
        }

        return $progress;
    }

    public function getOrdersByDay($day, array $attributes = ['added']){
        if(!filter_var($day, FILTER_VALIDATE_INT)){
            $day = date('d', strtotime($day));
        }

        if(!is_array($attributes)){
            $attributes = [$attributes];
        }

        $dayStart = strtotime($this->year.'-'.$this->month.'-'.$day);

        $orders = [];

        foreach($this->orders as $order){
            foreach ($attributes as $attribute){
                if(!$order->hasAttribute($attribute)){
                    throw new InvalidConfigException("Аттрибут {$attribute} в заказе не найден!");
                }

                if($attribute != 'added' && !empty($order->$attribute)){
                    $time = \DateTime::createFromFormat('Y-m-d H:i:s', $order->$attribute);

                    if($time != false){
                        $order->$attribute = $time->getTimestamp();
                    }
                }

                if($order->$attribute >= $dayStart && $order->$attribute < ($dayStart + 86400)){
                    $orders[] = $order;
                }
            }
        }

        return $orders;
    }

    public function getSalesStatsByDay($day){
        if($day > date('d') && $this->year == date('Y') && $this->month == date('m')){
            return [];
        }

        $earned = $confirmed = $done = 0;

        foreach($this->getOrdersByDay($day, ['added']) as $order){
            $earned += $order->originalSum;

            if($order->done == 1){
                $done += $order->actualAmount;
            }

            if($order->moneyConfirmed == 1){
                $confirmed += $order->actualAmount;
            }
        }

        return ['earned' => $earned, 'confirmed' =>  $confirmed, 'done'  =>  $done];
    }

    public function getSalesStats(){
        $dayFormatted = $this->year.'-'.$this->month.'-01';

        $stats = [];

        $lastDay = date('t', strtotime($dayFormatted));

        for($day = 1; $day <= $lastDay; $day++){
            $stats[] = array_merge(['date' => $this->year.'-'.(strlen($this->month) < 2 ? "0{$this->month}" : $this->month).'-'.($day < 10 ? "0{$day}" : $day)], $this->getSalesStatsByDay($day));
        }

        return $stats;
    }

}