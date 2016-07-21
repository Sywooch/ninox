<?php

use yii\bootstrap\Html;
use yii\helpers\Url;

$content = Html::tag('div',
    Html::tag('div',
        Html::tag('div',
            Html::img(\Yii::$app->params['cdn-link'].'/img/{{#if photo}}catalog/{{photo}}{{else}}noimage.png{{/if}}',
                ['class' => 'media-object']),
            [
                'style' =>  'width: 140px; overflow: hidden'
            ]),
        [
            'class' =>  'media-left',
        ]).
    Html::tag('div',
        Html::tag('h4', '{{name}}',
            ['class' => 'media-heading']).
        /*Html::tag('span', \Yii::t('shop', '{{#if code}}Код товара: {{code}}{{#if vendorCode}}<br>{{/if}}{{/if}}{{#if vendorCode}}Добавочный код: {{vendorCode}}{{/if}}'),
            ['class' => 'item-code']),*/
        Html::tag('b', '{{price}} грн.', ['class' => 'price']),
        ['class' => 'media-body']),
    [
        'class' =>  'media '
    ]
);

echo Html::a($content, Url::to(['/tovar/{{link}}-g{{ID}}', 'language' => \Yii::$app->language]), [
    'class' =>  'typeahead-list-item'
]);