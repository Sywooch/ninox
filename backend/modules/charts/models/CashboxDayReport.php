<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 28.06.16
 * Time: 17:25
 */

namespace backend\modules\charts\models;


use backend\models\CashboxMoney;
use common\models\Cost;
use common\models\History;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * @property History[] orders
 * @property CashboxMoney[] cashboxMoney
 * @property CashboxDayOperation[] operations
 * @property Cost[] cashboxExpenses
 */
class CashboxDayReport  extends Model
{

    public $date;

    public function getCashboxMoney(){
        return CashboxMoney::find()->where(['like', 'date', $this->date.'%', false])->andWhere(['in', 'cashbox', ArrayHelper::getColumn(\Yii::$app->params['configuration']->possibleCashboxes, 'ID')])->all();
    }

    public function getSum(){
        $sum = 0;

        foreach($this->cashboxMoney as $money){
            if($money->operation == $money::OPERATION_SELL){
                $sum += $money->amount;
            }
        }

        return $sum;
    }

    public function getExpenses(){
        $sum = 0;

        foreach($this->cashboxMoney as $money){
            if($money->operation == $money::OPERATION_SPEND){
                $sum += $money->amount;
            }
        }

        return $sum;
    }

    public function getShopOrders(){
        $orders = [];

        foreach($this->cashboxMoney as $money){
            if($money->operation == $money::OPERATION_SELL){
                $orders[] = $money->order;
            }
        }

        return $orders;
    }

    public function getSelfDeliveredOrders(){
        $orders = [];

        foreach($this->cashboxMoney as $money){
            if($money->operation == $money::OPERATION_SELF_DELIVERY){
                $orders[] = $money->order;
            }
        }

        return $orders;
    }
    public function getTook(){
        $toked = 0;

        foreach($this->cashboxMoney as $money){
            if($money->operation == $money::OPERATION_TAKE){
                $toked += $money->amount;
            }
        }

        return $toked;
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
        return $this->getCashboxMoney();
    }

}