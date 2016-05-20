<?php

use yii\bootstrap\Html;

echo Html::tag('div', Html::img(\Yii::$app->params['cdn-link'].'/img/catalog/'.$item->photo).Html::tag('div', '', ['class' => 'ico']), [
        'class' =>  'image'.($item->inOrder ? ' access' : ($item->notFounded ? ' denied' : ''))
    ]),
    Html::tag('span', $item->name, [
        'style' =>  'display: inline-block; margin-top: 20px; padding: 0px 30px;'
    ]),
    Html::tag('div',
        Html::tag('div',
            Html::input('string', null, $item->count, ['class' => 'inOrderCount']).
            Html::button('ОК', ['class' => 'btn btn-link saveCount']).
            Html::tag('div',
                Html::tag('span', $item->code).
                (!empty($item->good->BarCode2) ? Html::tag('small', $item->good->BarCode2) : '').
                Html::tag('span', "{$item->good->count} ШТ."),
                [
                    'class' => 'count'
                ]
            ), [
                'class' => 'items-count'
            ]
        ).
        Html::tag('div',
            Html::button('НЕ МОГУ НАЙТИ',
                [
                    'type'  =>  'button',
                    'class' =>  'medium-button button notFoundItem '.($item->inOrder == 1 ? 'gray-button' : 'red-button'),
                    ($item->inOrder == 1 ? 'disabled' : 'enabled') => 'true',
                    'style' =>  $item->inOrder != 1 ? '' : 'display: none'
                ]
            ).
            Html::button('УБРАТЬ ИЗ ЗАКАЗА',
                [
                    'type'  =>  'button',
                    'class' =>  'medium-button button fromOrder gray-button',
                    'style' =>  $item->inOrder == 1 ? '' : 'display: none'
                ]
            ).
            Html::button(($item->inOrder ? 'В ЗАКАЗЕ '.$item->count.' ШТ' : 'В ЗАКАЗ'),
                [
                    'type'  =>  'button',
                    'class' =>  'medium-button button toOrder '.($item->notFounded == 1 ? 'gray-button' : 'green-button'),
                    ($item->notFounded == 1 ? 'disabled' : 'enabled') => 'true'
                ]
            ), [
                'class' => 'buttons'
            ]
        ), [
            'class' => 'content'
        ]
    );