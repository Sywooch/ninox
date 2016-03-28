<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 05.01.16
 * Time: 18:07
 */

namespace frontend\helpers;


use frontend\models\Pricerule;

class PriceRuleHelper extends \common\helpers\PriceRuleHelper{

    public function init(){
        parent::init();
        if(!\Yii::$app->user->isGuest){
            $this->pricerules = array_merge(\Yii::$app->user->identity->getPriceRules(), $this->pricerules);
        }
        $this->cartSumm = !empty($this->cartSumm) ?
            $this->cartSumm : (isset(\Yii::$app->cart) ? \Yii::$app->cart->cartWholesaleRealSumm : 0);
    }
}