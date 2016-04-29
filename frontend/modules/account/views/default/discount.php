<?php
use yii\bootstrap\Html;

echo Html::tag('div',
    Html::tag('div',
        \frontend\widgets\ListGroupMenu::widget([
            'items'    => [
                [
                    'label' =>  'Личные данные',
                    'href'  =>  '/account'
                ],
                [
                    'label' =>  'Мои заказы',
                    'href'  =>  '/account/orders'
                ],
                [
                    'label' =>  'Моя скидка',
                    'href'  =>  '/account/discount'
                ],
                [
                    'label' =>  'Список желаний',
                    'href'  =>  '/account/wish-list'
                ],
                [
                    'label' =>  'Мои отзывы',
                    'href'  =>  '/account/reviews'
                ],
                [
                    'label' =>  'Возвраты',
                    'href'  =>  '/account/returns'
                ],
                [
                    'label' =>  'Ярмарка мастеров',
                    'href'  =>  '/account/yarmarka-masterov'
                ],
            ]
        ]),
        [
            'class' =>  'menu'
        ]),
    [
        'class' =>  'content'
    ]);