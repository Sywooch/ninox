<?php

use yii\bootstrap\Html;

echo Html::tag('div', Html::img('http://krasota-style.com.ua/img/catalog/'.$item->photo).Html::tag('div', '', ['class' => 'ico']), [
    'class' =>  'image'
]);

?>
<div class="content">
    <div class="items-count">
        <input type="text" value="<?=$item->count?>">
        <a href="">OK</a>
        <?=Html::tag('div', Html::tag('span', $item->code).Html::tag('span', "{$item->count} ШТ."), ['class' => 'count'])?>
    </div>
    <div class="buttons">
        <?=\yii\helpers\Html::button('В ЗАКАЗ', [
            'type'  =>  'submit',
            'class' =>  'green-button /*grey-button*/ medium-button button',
            'id'    =>  'submit'
        ])?>
        <?=\yii\helpers\Html::button('НЕ МОГУ НАЙТИ', [
            'type'  =>  'submit',
            'class' =>  'red-button /*grey-button*/ medium-button button',
            'id'    =>  'submit'
        ])?>
    </div>
</div>