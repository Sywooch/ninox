<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 10.03.16
 * Time: 11:49
 */
use common\helpers\Formatter;
use yii\bootstrap\Html;

echo Html::tag('div',
        Html::tag('div', html::tag('div', '', ['class' => 'desire-ico']),
             ['class' => 'icons-fav-bask']).
        Html::tag('span', 'good name').
        Html::tag('span', Formatter::getFormattedPrice(0), ['class' => 'price'])
).
    Html::tag('div', Html::img('http://krasota-style.com.ua/img/catalog/'));