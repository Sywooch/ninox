<?php
use frontend\helpers\PriceRuleHelper;
use yii\bootstrap\Html;

$helper = new PriceRuleHelper();

$rules = '';

foreach($helper->pricerules as $rule){
    if($rule->customerRule == 1 || true){
        $rules .= Html::tag('div', $rule->humanFriendly, ['class' => 'one-rule']);
    }
}

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
    Html::tag('div', $rules,
        [
            'class' =>  'user-data-content discount'
        ]
    ),
    [
        'class' =>  'content'
    ]
);