<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 13.02.16
 * Time: 15:32
 */

namespace cashbox\helpers;

use cashbox\models\CustomerPricerule;

class PriceRuleHelper extends \common\helpers\PriceRuleHelper{


    public function init()
    {
        parent::init();
        if(\Yii::$app->request->cookies->getValue('cashboxCurrentCustomer', false) ||
            \Yii::$app->request->post("customerID")){
            $cookie = \Yii::$app->request->cookies->getValue('cashboxCurrentCustomer', false);
            $request = \Yii::$app->request->post("customerID");
            $customerID = !empty($request) && $request != $cookie ? $request : $cookie;
            $this->pricerules = array_merge(CustomerPricerule::find()
                ->where(['customerID' => $customerID, 'Enabled' => 1])
                ->orderBy(['Priority' => SORT_DESC])
                ->all(), $this->pricerules);
        }
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