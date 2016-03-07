<?php

use yii\bootstrap\Html;

$content = Html::tag('div',
    Html::tag('div',
        Html::tag('div',
            Html::img('http://krasota-style.com.ua/img/{{#if photo}}catalog/{{photo}}{{else}}noimage.png{{/if}}',
                ['class' => 'media-object']),
            [
                'style' =>  'max-width: 40px; overflow: hidden'
            ]),
            [
            'class' =>  'media-left',
        ]).
    Html::tag('div',
        Html::tag('h4', '{{name}}',
            ['class' => 'media-heading']).
        Html::tag('span', '{{#if code}}Код товара: {{code}}{{#if vendorCode}}<br>{{/if}}{{/if}}{{#if vendorCode}}Добавочный код: {{vendorCode}}{{/if}}',
            ['class' => 'item-code']).
        Html::tag('span', '{{category}}', ['class' => 'category']).
        Html::tag('div', Html::tag('span',
            '{{#if disabled}}'.Html::tag('span', 'отключен', ['class' => 'label label-danger label-xs']).'&nbsp;{{/if}}'.
            '{{#if ended}}'.Html::tag('span', 'закончился', ['class' => 'label label-warning label-xs']).'&nbsp;{{/if}}'.
            '{{#if sale}}'.Html::tag('span', 'на распродаже', ['class' => 'label label-success label-xs']).'&nbsp;{{/if}}'
        )),
        ['class' => 'media-body']),
    [
        'class' =>  'media'
    ]
);

echo Html::a($content, '/goods/view/{{ID}}', [
    'class' =>  'typeahead-list-item'
]);