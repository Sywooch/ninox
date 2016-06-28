<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 28.06.16
 * Time: 17:25
 */

namespace backend\modules\charts\models;


use common\models\CashboxMoney;
use common\models\Cost;
use common\models\CostsType;
use common\models\History;
use yii\base\Model;

/**
 * @property History[] orders
 * @property CashboxMoney[] cashboxMoney
 */
class CashboxDayReport  extends Model
{

    public $date;

    public function getOrders(){
        $dayStart = strtotime($this->date);
        $dayEnd = strtotime($this->date) + 86400;

        return History::find()
            ->where(['or', ['sourceType' => History::SOURCETYPE_SHOP], ['sourceType' => History::SOURCETYPE_INTERNET, 'paymentType' => 3]])
            ->andWhere("`added` > '{$dayStart}' AND `added` < '{$dayEnd}'")
            //->andWhere(['or', "`added` > '{$dayStart}' AND `added` < '{$dayEnd}'", "`moneyConfirmedDate` LIKE '{$this->date}'"])
            ->all();
    }

    public function getCashboxMoney(){
        return CashboxMoney::find()->where(['like', 'date', $this->date.'%', false])->all();
    }

    public function getSum(){
        $sum = 0;

        foreach($this->orders as $order){
            $sum += $order->payedAmount;
        }

        return $sum;
    }

    public function getExpenses(){
        $expenses = 0;

        foreach(Cost::find()->where(['date' => $this->date, 'costId' => CostsType::find()->select('ID')->where(['type' => 'cashboxExpenses'])->scalar()])->each() as $cost){
            $expenses += $cost->costSumm;
        }

        return $expenses;
    }

    public function getTook(){
        $tooked = 0;

        foreach($this->cashboxMoney as $money){
            if($money->operation == $money::OPERATION_REFUND){
                $tooked = $money->amount;
            }
        }

        return $tooked;
    }

    public function getAdded(){
        $added = 0;

        foreach($this->cashboxMoney as $money){
            if($money->operation == $money::OPERATION_SELL){
                $added = $money->amount;
            }
        }

        return $added;
    }

    public function getOperations(){

    }

}