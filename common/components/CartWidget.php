<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 22.09.15
 * Time: 15:10
 */

namespace common\components;


use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\helpers\Html;

class CartWidget extends Widget{

    public $remodalInstance;

    public function init(){

    }

    public function run(){
        $return = '';

        $return .= $this->remodalInstance->renderButton([
            'label' =>  Html::tag('span').Html::tag('span', '150.00 '.\Yii::$app->params['domainInfo']['currencyShortName'], [
                    'class' => 'summ'
                ]),
            'class' =>  'openCart'
        ]);
        $return .= Html::tag('ul', Html::tag('li', 'В вашей корзине '.Html::tag('div', Html::tag('span', '1 товар', [
                'class' =>  'inCartItemsCount'
            ]))).Html::tag('li', $this->remodalInstance->renderButton([
                'label' =>  'оформить заказ',
                'class' =>  'openCart'
            ])));

        return $return;
    }
}