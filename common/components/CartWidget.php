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



    public function init(){

    }

    public function run(){
        echo Html::tag('a', Html::tag('span').Html::tag('span', '150.00 '.\Yii::$app->params['currencyShortName'], [
            'class' => 'summ'
        ]), [
            'class' =>  'openCart'
        ]);
        echo '<ul>', Html::tag('li', 'В вашей корзине '.Html::tag('div', Html::tag('span', '1 товар', [
                'class' =>  'inCartItemsCount'
            ])));


        echo '</ul>';
    }
}