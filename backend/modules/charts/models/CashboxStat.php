<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 07.07.16
 * Time: 19:39
 */

namespace backend\modules\charts\models;


use common\models\CashboxMoney;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * @property mixed operations
 * @property mixed actualOperations
 */
class CashboxStat extends Model
{

    public $date;
    public $_operations;

    public function getOperations(){
        if(!empty($this->_operations)){
            return $this->_operations;
        }

        return $this->_operations = CashboxMoney::find()->andWhere(['in', 'cashbox', ArrayHelper::getColumn(\Yii::$app->params['configuration']->possibleCashboxes, 'ID')])->all();
    }

    public function getActualOperations(){
        $operations = [];

        foreach($this->operations as $operation){
            if($operation->date >= $this->date){
                $operations[] = $operation;
            }
        }

        return $operations;
    }

    public function getOrdersSum(){
        $sum = 0;

        foreach($this->actualOperations as $operation){
            if($operation->operation == $operation::OPERATION_SELL || $operation->operation == $operation::OPERATION_SELF_DELIVERY){
                $sum += $operation->amount;
            }else if($operation->operation == $operation::OPERATION_REFUND){
                $sum -= $operation->amount;
            }
        }

        return $sum;
    }

    public function getSpend(){
        $spend = 0;

        foreach($this->actualOperations as $operation){
            if($operation->operation == $operation::OPERATION_SPEND){
                $spend += $operation->amount;
            }
        }

        return $spend;
    }

    public function getCurrentSum(){
        $sum = 0;

        foreach($this->operations as $operation){
            switch($operation->operation){
                case $operation::OPERATION_PUT:
                case $operation::OPERATION_SELL:
                case $operation::OPERATION_SELF_DELIVERY:
                    $sum += $operation->amount;
                    break;
                case $operation::OPERATION_TAKE:
                case $operation::OPERATION_SPEND:
                case $operation::OPERATION_REFUND:
                    $sum -= $operation->amount;
                    break;
            }
        }

        return $sum;
    }

}