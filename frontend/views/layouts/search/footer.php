<?php

use yii\helpers\Html;

$content = Html::tag('div',
    Html::tag('div',
        Html::tag('div',
            Html::img(\Yii::$app->params['cdn-link'].'/img/{{#if photo}}catalog/{{photo}}{{else}}noimage.png{{/if}}',
                ['class' => 'media-object']),
            [
                'style' =>  'max-width: 40px; overflow: hidden'
            ]),
        [
            'class' =>  'media-left',
        ]).
    Html::tag('div',
        Html::tag('h4', \Yii::t('shop', 'Ко всем товарам из поиска ->'),
            ['class' => 'media-heading']).
        Html::tag('span', '{{category}}', ['class' => 'category']),
        ['class' => 'media-body']),
    [
        'class' =>  'media'
    ]
);

echo Html::a($content, '/search/{{query}}', [
    'class' =>  'typeahead-list-item'
]);