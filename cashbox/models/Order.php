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
            'originalSum'       =>  \Yii::$app->cashbox->sum
        ], false);
    }

    public function getItems()
    {
        return $this->hasMany(AssemblyItem::className(), ['orderID' => 'id']);
    }

    /**
     * @param $itemID
     * @return bool|\common\models\SborkaItem
     */
    public function getItem($itemID){
        foreach($this->items as $item){
            if($item->itemID == $itemID){
                return $item;
            }
        }

        return false;
    }

}