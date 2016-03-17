<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 13.02.16
 * Time: 15:32
 */

namespace cashbox\helpers;

class PriceRuleHelper extends \common\helpers\PriceRuleHelper{


    public function init(){
        parent::init();
        $this->cartSumm = isset($this->cartSumm) ?
            $this->cartSumm : (isset(\Yii::$app->cashbox) ? \Yii::$app->cashbox->sum : 0);
    }

    public function recalc(&$model, $category = false)
    {
        parent::recalc($model, $category);

        return $model->priceModified;
    }

    protected function recalcItem(&$model, $rule, $category = false){
        parent::recalcItem($model, $rule, $category);
        return $model;
    }
}