<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 10.02.16
 * Time: 16:39
 */

namespace cashbox\models;


use yii\web\BadRequestHttpException;

class AssemblyItem extends \common\models\SborkaItem{

    public function loadCashboxItem($cashboxItem, $createdOrder){
        if($cashboxItem instanceof CashboxItem == false){
            throw new BadRequestHttpException("");
        }

        $this->setAttributes([
            'orderID'       =>  $createdOrder,
            'itemID'        =>  $cashboxItem->itemID,
            'name'          =>  $cashboxItem->name,
            'count'         =>  $cashboxItem->count,
            'originalCount' =>  $cashboxItem->count,
            'originalPrice' =>  $cashboxItem->originalPrice,
            'discountSize'  =>  $cashboxItem->discountSize,
            'discountType'  =>  $cashboxItem->discountType,
            'priceRuleID'   =>  $cashboxItem->priceRuleID,
            'category'      =>  $cashboxItem->category,
            'customerRule'  =>  $cashboxItem->customerRule
        ], false);


    }

}