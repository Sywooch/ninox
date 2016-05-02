<?php

use bobroid\remodal\Remodal;
use yii\bootstrap\Html;
use yii\helpers\Url;

echo Html::beginTag('div', ['class' => 'content']),
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
        ])?>
    <div class="user-data-content">
        <?=Html::tag('div', Html::tag('div',
                Html::tag('div',
                    Html::tag('div',
                        Html::tag('div',
                            Html::tag('i', '',
                                [
                                    'class' => 'icon icon-note'
                                ]).
                            ' '.
                            \Yii::t('shop', 'Личные данные'),
                            [
                                'class' => 'myriad'
                            ]).
                        Html::tag('div',
                            Html::tag('div',
                                Html::tag('div',
                                    \Yii::t('shop', 'Имя'),
                                    [
                                        'class' => 'semi'
                                    ]).
                                Html::tag('div',
                                    \Yii::$app->user->identity->name.' '.\Yii::$app->user->identity->surname,
                                    [
                                        'class' => 'personal-data'
                                    ]),
                                [
                                    'class' => 'name'
                                ]).
                            Html::tag('div',
                                Html::tag('div',
                                    \Yii::t('shop', 'Телефон'),
                                    [
                                        'class' => 'semi'
                                    ]).
                                Html::tag('div',
                                    Html::tag('div',
                                        \Yii::$app->formatter->asPhone(\Yii::$app->user->identity->phone),
                                        [
                                            'class' => ''
                                        ]),
                                    [
                                        'class' => ''
                                    ]),
                                [
                                    'class' => 'phone'
                                ]).
                            Html::tag('div',
                                Html::tag('div',
                                    \Yii::t('shop', 'Эл. почта'),
                                    [
                                        'class' => 'semi'
                                    ]).
                                Html::tag('div',
                                    \Yii::$app->user->identity->email,
                                    [
                                        'class' => 'personal-data'
                                    ]),
                                [
                                    'class' => 'email'
                                ]),
                            [
                                'class' =>  'semi-font data'
                            ]),
                        [
                            'class' =>  'user-account personal-data data'
                        ]),
                    [
                        'class' =>  'page'
                    ]).
                \yii\widgets\ListView::widget([
                    'dataProvider'  =>  $lastOrderProvider,
                    'itemView'      =>  function($model){
                        return ' г. Киев, ул. Гетьмана Вадима, 1А/11 ';
                    },
                    'showOnEmpty' => false,
                    'emptyTextOptions'  =>  [
                        'style' =>  'display: none'
                    ],
                    'options'   =>  [
                        'class' =>  'page'
                    ],
                    'itemOptions'   =>  [
                        'class' =>  'address'
                    ],
                    'layout'    =>  Html::tag('div',
                        Html::tag('div',
                            Html::tag('div',
                                Html::tag('i', '', ['class' => 'icon icon-delivery-car']).\Yii::t('shop', 'Адрес для доставки:'),
                                [
                                    'class' =>  'color'
                                ]
                            ).
                            Html::tag('div',
                                '{items}',
                                [
                                    'class' =>  'address-data'
                                ]),
                            [
                                'class' =>  'semi-font'
                            ]
                        ),
                        [
                            'class' =>  'user-account'
                        ]
                    )
                ]),
                [
                    'class' => 'pages'
                ]).
            Html::tag('div',
                Html::tag('div',
                    Remodal::widget([
                        'confirmButton'	=>	false,
                        'id'			=>	'editAccount',
                        'cancelButton'	=>	false,
                        'addRandomToID'	=>	false,
                        'content'		=>	$this->render('_account_edit_modal'),
                        'buttonOptions'	=>	[
                            'label'		=>	\Yii::t('shop', 'Редактировать')
                        ],
                    ]).
                    Remodal::widget([
                        'confirmButton'	=>	false,
                        'id'			=>	'changePassword',
                        'cancelButton'	=>	false,
                        'addRandomToID'	=>	false,
                        'content'		=>	$this->render('_change_password_modal'),
                        'buttonOptions'	=>	[
                            'label'		=>	\Yii::t('shop', 'Изменить пароль')
                        ],
                    ]).
                    Html::a('Выйти',
                        Url::to('/logout'), [
                            'data-method'   =>  'post'
                        ]), [
                        'class' => 'border'
                    ]),
                [
                    'class' =>  'user-options'
                ]),
            [
                'class' => 'user-account-data'
            ]).
        Html::beginTag('div', ['class' =>   'user-money-discount']);

        if(!empty(\Yii::$app->user->identity->money)){
            echo Html::tag('div',
                Html::tag('div',
                    Html::tag('div',
                        Html::tag('i', '', ['class' => 'icon icon-money-pig']).\Yii::t('shop', 'Личный счёт')
                    ),
                    [
                        'class' =>  'myriad'
                    ]).
                Html::tag('div',
                    Html::tag('div',
                        Html::tag('div', \Yii::t('shop', 'На вашем счету:'), ['class' => 'address']).
                        Html::tag('div', \Yii::$app->user->identity->money, ['class' => 'account-money']),
                        [
                            'class' =>  'address-data'
                        ]),
                    [
                        'class' =>  'semi-font'
                    ]),
                [
                    'class' =>  'user-account personal-discount'
                ]);
        }

        if(!empty(\Yii::$app->user->identity->cardNumber)){
            echo Html::tag('div',
                Html::tag('div',
                    Html::tag('div',
                        Html::tag('i', '', ['class' => 'icon icon-your-discont']).\Yii::t('shop', 'Ваша скидка')
                    ),
                    [
                        'class' =>  'myriad'
                    ]).
                Html::tag('div',
                    Html::tag('div',
                        Html::tag('div', \Yii::t('shop', 'Дисконтная карта - 2% на все товары на сайте'), ['class' => 'address']).
                        Html::tag('div', \Yii::t('shop', 'Персональная скидка - 15% на <a>избранные</a> категории'), ['class' => 'address']),
                        [
                            'class' =>  'address-data'
                        ]),
                    [
                        'class' =>  'semi-font'
                    ]),
                [
                    'class' =>  'user-account personal-discount'
                ]);
        }

        echo Html::endTag('div');
     if(!empty($lastOrderProvider->getModels()) && (time() - strtotime(\Yii::$app->user->identity->giveFeedbackClosed)) > 3600){
            $js = <<<'JS'
$("#reviews .icon.icon-exit").on('click', function(e){
    $.ajax({
        type: 'POST',
		url: '/account/betterlistclosed',
		success: function(data){
		    var reviews = $('.reviews');
		
		    reviews.animate({height: (reviews.css('display') == 'none' ? 'show' : 'hide')}, 500);
        }
    });
});
JS;

$this->registerJs($js);

            echo $this->render('_account_leaveFeedback', [
                'customerBuyedItems'    =>  $buyedItems
            ]);
        }

echo Html::endTag('div'),
    Html::endTag('div');