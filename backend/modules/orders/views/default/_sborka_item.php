<?php

use yii\bootstrap\Html;

echo Html::tag('div', Html::img('http://krasota-style.com.ua/img/catalog/'.$item->photo).Html::tag('div', '', ['class' => 'ico']), [
        'class' =>  'image'.($item->inOrder ? ' access' : ($item->notFounded ? ' denied' : ''))
    ]),
    Html::tag('div',
        Html::tag('div',
            Html::input('string', null, $item->count, ['class' => 'inOrderCount']).
            Html::button('ОК', ['class' => 'btn btn-link saveCount']).
            Html::tag('div',
                Html::tag('span', $item->code).
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