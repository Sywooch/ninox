<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 13.02.16
 * Time: 15:32
 */

namespace cashbox\helpers;

class PriceRuleHelper extends \common\helpers\PriceRuleHelper{

    protected function checkDocumentSumm($term, &$termsCount, &$discount){
        $termsCount++;
        $cartSumm = !empty($this->cartSumm) ? $this->cartSumm : \Yii::$app->cashbox->sum;
        foreach($term as $ds){
            if(($cartSumm == $ds['term'] && $ds['type'] == '=') || ($cartSumm >= $ds['term'] && $ds['type'] == '>=') || ($cartSumm <= $ds['term'] && $ds['type'] == '<=')){
                $discount += 1;
                break;
            }
        }
    }

    protected function recalcItem($model, $rule){
        $tempModel = parent::recalcItem($model, $rule, false);

        if($tempModel){
            $tempModel->priceModified = true;
            return $tempModel;
        }

        return $model;
    }

}