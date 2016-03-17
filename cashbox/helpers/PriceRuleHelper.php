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


    public function init()
    {
        parent::init();
        $this->cartSumm = isset($this->cartSumm) ?
            $this->cartSumm : (isset(\Yii::$app->cashbox) ? \Yii::$app->cashbox->sum : 0);
    }


    public function recalc(&$model, $category = false)
    {
        parent::recalc($model, $category);

        return $model->priceModified;
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