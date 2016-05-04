<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 12/9/2015
 * Time: 2:06 PM
 */
use yii\bootstrap\Html;
use yii\widgets\ListView;

$js = <<<'JS'
$("body").on('click', ".spoiler-title", function(){
    $(this).parent().toggleClass("showw").children(".spoiler-body").slideToggle("medium");
});
JS;

$this->registerJs($js);

echo Html::tag('div',
    Html::tag('div', \frontend\widgets\ListGroupMenu::widget([
        'items'    => [
            [
                'label' =>  \Yii::t('shop', 'Личные данные'),
                'href'  =>  '/account'
            ],
            [
                'label' =>  \Yii::t('shop', 'Мои заказы'),
                'href'  =>  '/account/orders'
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
    ]), ['class' => 'menu']).
    Html::tag('div',
        Html::tag('div',
            Html::tag('i', '', ['class' => 'icon icon-box']).' '.\Yii::t('shop', 'Мои заказы'),
            [
                'class' =>  'user-account box myriad'
            ]
        ).
        Html::tag('div',
            ListView::widget([
                'dataProvider'  =>  $ordersDataProvider,
                'summary'       =>  false,
                'itemView'      =>  '_order',
            ]),
            [
                'class' =>  'orders'
            ]
        ),
        [
            'class' =>  'user-data-content'
        ]
    ),
    [
        'class' =>  'content'
    ]
);