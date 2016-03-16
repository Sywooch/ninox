<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 13.02.16
 * Time: 15:32
 */

namespace cashbox\helpers;

use common\models\Pricerule;

class PriceRuleHelper extends \common\helpers\PriceRuleHelper{

    protected $pricerules = [];

    public function init(){
        if(empty($this->pricerules)){
            $this->pricerules = Pricerule::find()->where(['Enabled' => 1])->orderBy('`Priority`')->all();
        }
    }

    public function recalc(&$model, $category = false)
    {
        if($model->discountType == 0 || $model->priceRuleID != 0){
            foreach($this->pricerules as $rule){
                if($this->recalcItem($model, $rule, false)){
                    return true;
                }
            }
            if($model->priceRuleID != 0){
                $model->priceModified = true;
                $model->priceRuleID = 0;
                $model->discountType = 0;
                $model->discountSize = 0;
                $model->customerRule = 0;

                return false;
            }
        }
        $model->priceModified = false;

        return false;
    }

    protected function checkDocumentSumm($term, &$termsCount, &$discount){
        $termsCount++;

        $cartSum = isset($this->cartSumm) ? $this->cartSumm : \Yii::$app->cashbox->sum;
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