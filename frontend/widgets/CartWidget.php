<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 22.09.15
 * Time: 15:10
 */

namespace frontend\widgets;


use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class CartWidget
 * @package frontend\widgets
 * @author  Nikolai Gilko   <n.gilko@gmail.com>
 */

class CartWidget extends Widget{

    /**
     * @type \bobroid\remodal\Remodal
     */
    public $remodalInstance;

    public function init(){

    }

    public function run(){
        $return = '';

        $return .= Html::tag('div', '', ['class' => 'basket-icon']);
        $return .= Html::tag('div', \Yii::$app->cart->itemsCount, ['class' => 'count items-count']);
        $return .= Html::tag('span', 'Корзина');

        return Html::tag('a', $return, ['class' => 'basket', 'href' => '#modalCart']);
    }
}