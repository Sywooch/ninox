<?php
use yii\bootstrap\Html;
use yii\helpers\Url;

$good = $model;

/** @var \common\models\Good $good */
$itemOptions = [
    'class' =>  'thumbnail'.($good->enabled == 1 ? ' bg-success' : ' bg-danger').($good->Deleted == 1 ? ' bg-very-danger' : ''),
    'data-value-goodID' =>  $good->ID,
];

if($good->Deleted == 1){
    $itemOptions['data-attribute-deleted'] = true;
}

$caption = '<dl>
            <dt>Название:</dt>
            '.Html::tag('dd', $good->name).'
            <dt>Код товара:</dt>
            <dd>'.$good->Code.'</dd>
            <dt>Цена опт:</dt>
            <dd>'.$good->wholesalePrice.' грн.</dd>
            <dt>Цена розница:</dt>
            <dd>'.$good->retailPrice.' грн.</dd>
            <dt>Цена в валюте:</dt>
            <dd>'.$good->anotherCurrencyValue.' '.$good->anotherCurrencyTag.'</dd>
            <dt>Остаток:</dt>
            <dd>'.$good->count.' шт.</dd>
            <dt>Создан:</dt>
            <dd>'.($good->tovdate != '0000-00-00 00:00:00' ? \Yii::$app->formatter->asDatetime($good->tovdate, "php:d.m.Y H:i") : "-").'</dd>
            <dt>Отключен:</dt>
            <dd>'.($good->otkl_time != '0000-00-00 00:00:00' ? \Yii::$app->formatter->asDatetime($good->otkl_time, "php:d.m.Y H:i") : "-").'</dd>
        </dl>
        <div class="btn-group" role="group" aria-label="...">
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    Действия <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="/goods/view/'.$good->ID.'?act=edit" data-pjax="0">Редактировать</a></li>
                    <li id="good-state"><a style="cursor: pointer;" class="changeState-btn">'.($good->enabled == "1" ? "Отключить" : "Включить").'</a></li>
                    <li><a style="cursor: pointer;" class="up-btn">Поднять товар</a></li>
                    <li><a style="cursor: pointer;" class="print-btn">Печать</a></li>'.
                    ($good->enabled != 0 && $good->Deleted != 1 ? Html::tag('li', Html::a('Посмотреть на сайте', Url::to(['https://krasota-style.com.ua/tovar/'.$good->link.'-g'.$good->ID]))) : '')
                    .'<li class="divider"></li>
                    <li id="deleted-state"><a style="cursor: pointer;" class="delete-btn">'.($good->Deleted == 1 ? "Восстановить" : "Удалить").'</a></li>
                </ul>
            </div>
            <a href="/goods/view/'.$good->ID.'" type="button" class="btn btn-default" data-pjax="0">Подробнее</a>
        </div>';

echo Html::tag('div',
    Html::img(\Yii::$app->params['cdn-link'].'/img/catalog/sm/'.$good->photo, [
        'alt'   =>  $good->Name,
        'class' =>  ($good->discountType != 0 ? 'good-sale' : '')
    ]).
    Html::tag('div', $caption, ['class' => 'caption']), $itemOptions
);

?>