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


    public function init(){
        parent::init();
        $this->cartSumm = isset($this->cartSumm) ?
            $this->cartSumm : (isset(\Yii::$app->cashbox) ? \Yii::$app->cashbox->sum : 0);
    }

    public function recalc(&$model, $category = false){
        if($model->discountType == 0 || $model->priceRuleID != 0){
            foreach($this->pricerules as $rule){
                if(parent::recalcItem($model, $rule, false)){
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

    /**
     * @param \common\models\Good $model
     * @param Pricerule $rule
     * @param bool $category
     * @return \common\models\Good
     * @deprecated
     */
    protected function recalcItem(&$model, $rule, $category = false){
        parent::recalcItem($model, $rule, $category);
        return $model;
    }
}