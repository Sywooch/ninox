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

    public $pricerules;

    public function init(){
        $this->pricerules = Pricerule::find()->where(['Enabled' => 1])->orderBy('`Priority`')->all();
        if(!\Yii::$app->user->isGuest){
            $this->pricerules = array_merge(\Yii::$app->user->identity->getPriceRules(), $this->pricerules);
        }
	    foreach($this->pricerules as $rule){
		    $rule->asArray();
	    }
    }

}