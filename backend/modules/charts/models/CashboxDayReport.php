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
 * @property CashboxDayOperation[] operations
 * @property Cost[] cashboxExpenses
 */
class CashboxDayReport  extends Model
{

    public $date;

    public function getOrders(){
        $dayStart = strtotime($this->date);
        $dayEnd = strtotime($this->date) + 86400;

        $shopType = History::SOURCETYPE_SHOP;
        $internetType = History::SOURCETYPE_INTERNET;

        return History::find()
            ->where(['or', "`sourceType` = '{$shopType}' AND `added` > '{$dayStart}' AND `added` < '{$dayEnd}'", "`sourceType` = '{$internetType}' AND `paymentType` = '3' AND `moneyConfirmedDate` LIKE '{$this->date}%'"])
            ->andWhere(['orderSource' => \Yii::$app->params['configuration']->id])
            //->andWhere("`added` > '{$dayStart}' AND `added` < '{$dayEnd}'")
            //->andWhere(['or', "`added` > '{$dayStart}' AND `added` < '{$dayEnd}'", "`moneyConfirmedDate` LIKE '{$this->date}'"])
            ->all();
    }

    public function getCashboxMoney(){
        return CashboxMoney::find()->where(['like', 'date', $this->date.'%', false])->all();
    }

    public function getSum(){
        $sum = 0;

        foreach($this->orders as $order){
            $sum += $order->actualAmount;
        }

        return $sum;
    }

    public function getCashboxExpenses(){
        return Cost::find()->where(['date' => $this->date, 'costId' => CostsType::find()->select('ID')->where(['type' => 'cashboxExpenses'])->scalar()])->all();
    }

    public function getExpenses(){
        $expenses = 0;

        foreach($this->cashboxExpenses as $cost){
            $expenses += $cost->costSumm;
        }

        return $expenses;
    }

    public function getTook(){
        $tooked = 0;

        foreach($this->cashboxMoney as $money){
            if($money->operation == $money::OPERATION_TAKE){
                $tooked += $money->amount;
            }
        }

        return $tooked;
    }

    public function getAdded(){
        $added = 0;

        foreach($this->cashboxMoney as $money){
            if($money->operation == $money::OPERATION_PUT){
                $added += $money->amount;
            }
        }

        return $added;
    }
    
    public function getOperations(){
        $operations = [];

        foreach($this->orders as $order){
            $operations[] = new CashboxDayOperation([
                'date'              =>  \Yii::$app->formatter->asDatetime($order->added, 'php:Y-m-d H:i:s'),
                'type'              =>  $order->sourceType == $order::SOURCETYPE_SHOP ? CashboxDayOperation::TYPE_SHOP_BUY : CashboxDayOperation::TYPE_SELF_DELIVERY,
                'orderID'           =>  $order->id,
                'sum'               =>  $order->actualAmount,
                'responsibleUser'   =>  $order->responsibleUserID
            ]);
        }

        foreach($this->cashboxMoney as $operation){
            $type = null;

            switch($operation->operation){
                case $operation::OPERATION_TAKE:
                    $type = CashboxDayOperation::TYPE_CASHBOX_GET;
                    break;
                case $operation::OPERATION_PUT:
                    $type = CashboxDayOperation::TYPE_CASHBOX_PUT;
                    break;
            }

            if(!empty($type)){
                $operations[] = new CashboxDayOperation([
                    'date'              =>  $operation->date,
                    'type'              =>  $type,
                    'orderID'           =>  $operation->order,
                    'sum'               =>  $operation->amount,
                    'responsibleUser'   =>  $operation->responsibleUser
                ]);
            }
        }

        foreach($this->cashboxExpenses as $expense){
            $operations[] = new CashboxDayOperation([
                'date'              =>  $expense->date,
                'type'              =>  CashboxDayOperation::TYPE_CASHBOX_SPEND,
                'sum'               =>  $expense->costSumm,
                'responsibleUser'   =>  $expense->creator
            ]);
        }

        return $operations;
    }

}