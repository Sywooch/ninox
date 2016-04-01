<?php
use common\helpers\Formatter;
use yii\bootstrap\Html;

echo Html::tag('span', $good->Code, ['class' => 'item-id']),
    Html::tag('div', Html::img('http://krasota-style.com.ua/img/catalog/'.$good->ico), ['class' => 'item-image']),
    Html::tag('span', $good->Name, ['class' => 'short-description']),
    Html::tag('div', Html::tag('span', Formatter::getFormattedPrice($good->wholesalePrice), ['class' => 'wholesale-price semi-bold']).
        Html::tag('span', Formatter::getFormattedPrice($good->retailPrice), ['class' => 'retail-price']).
        Html::tag('div', '', ['class' => 'goods-basket']), [
        'class' =>  'price-and-order'
    ]);
//$this->render('/site/_shop_item/_shop_item_wish', ['model' => $good])