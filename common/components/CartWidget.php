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

        $return .= Html::tag('div', '', ['class' => 'basket-icon']);
        $return .= Html::tag('div', \Yii::$app->cart->itemsCount, ['class' => 'count']);
        $return .= Html::tag('span', 'Корзина');

        return Html::tag('a', $return, ['class' => 'basket', 'href' => '#modalCart']);
    }
}