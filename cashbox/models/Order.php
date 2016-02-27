<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 10.02.16
 * Time: 16:46
 */

namespace cashbox\models;

class Order extends \common\models\History{

    public function loadCashboxOrder($cashboxOrder, $amount){
        if($cashboxOrder instanceof CashboxOrder == false){
            throw new \BadMethodCallException();
        }

        $this->setAttributes([
            'actualAmount'      =>  $amount,
            'responsibleUserID' =>  \Yii::$app->cashbox->responsibleUser,
            'originalSum'       =>  \Yii::$app->cashbox->order->sum
        ], false);
    }

}