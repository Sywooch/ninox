<?php
use yii\bootstrap\Html;
use yii\widgets\ListView;

echo Html::tag('div',
    Html::tag('div',
        \frontend\widgets\ListGroupMenu::widget([
            'items'    => [
                [
                    'label' =>  \Yii::t('shop', 'Мои заказы'),
                    'href'  =>  '/account/orders'
                ],
                [
                    'label' =>  \Yii::t('shop', 'Личные данные'),
                    'href'  =>  '/account'
                ],
                [
                    'label' =>  \Yii::t('shop', 'Моя скидка'),
                    'href'  =>  '/account/discount'
                ],
                [
                    'label' =>  \Yii::t('shop', 'Список желаний'),
                    'href'  =>  '/account/wish-list'
                ],
                [
                    'label' =>  \Yii::t('shop', 'Мои отзывы'),
                    'href'  =>  '/account/reviews'
                ],
                /*[
                    'label' =>  \Yii::t('shop', 'Возвраты'),
                    'href'  =>  '/account/returns'
                ],
                [
                    'label' =>  \Yii::t('shop', 'Ярмарка мастеров'),
                    'href'  =>  '/account/yarmarka-masterov'
                ],*/
            ]
        ]),
        [
            'class' =>  'menu'
        ]).
    Html::tag('div',
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
        'class' =>  'user-data-content'
        ]),
    [
        'class' =>  'content'
    ]);