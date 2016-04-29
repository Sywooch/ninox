<?php
use yii\bootstrap\Html;
use yii\widgets\ListView;

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
        ]).
    ListView::widget([
        'dataProvider'  =>  $reviews,
        'itemView'          =>  function($review){
            return Html::tag('div',
                Html::tag('span', Html::a($review->goodID, '/tovar/-g'.$review->goodID).'&nbsp;'.Html::tag('small', \Yii::$app->formatter->asDate($review->date, 'php:d.m.Y H:i'))).
                Html::tag('p', $review->what),
                [
                    'class' =>  ($review->show == 1 ? 'success' : 'warning')
                ]);
        }
    ]),
    [
        'class' =>  'content'
    ]);