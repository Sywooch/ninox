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
        $cartSum = !empty($this->cartSumm) ? $this->cartSumm : \Yii::$app->cashbox->sum;
        foreach($term as $ds){
            if(($cartSum == $ds['term'] && $ds['type'] == '=') || ($cartSum >= $ds['term'] && $ds['type'] == '>=') || ($cartSum <= $ds['term'] && $ds['type'] == '<=')){
                $discount += 1;
                break;
            }
        }
    }

    protected function recalcItem(&$model, $rule, $category = false){
        parent::recalcItem($model, $rule, $category);
        return $model;
    }

}