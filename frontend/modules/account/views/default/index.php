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
        <div class="user-account-data">
            <div class="pages">
                <?=Html::tag('div',
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
                    ])?>
                <div class="page">
                    <div class="user-account ">
                        <div class="semi-font">
                            <div class="color">
                                 <i class="icon icon-delivery-car"></i> Адрес для доставки:
                            </div>
                            <div class="address-data">
                                <div class="address">
                                    г. Киев, ул. Гетьмана Вадима, 1А/11
                                </div>
                                <div class="address">
                                    Ивано-Франковск обл., г. Коломья Новая Почта 2 отд.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?=Html::tag('div',
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
                ])?>
        </div>
        <div class="user-money-discount">
            <div class="user-account personal-discount">
                <div class="myriad">
                    <i class="icon icon-money-pig"></i> <?=\Yii::t('shop', 'Личный счет')?>
                </div>
                <?=Html::tag('div',
                    Html::tag('div',
                        Html::tag('div', \Yii::t('shop', 'На вашем счету:'), ['class' => 'address']).
                        Html::tag('div', \Yii::$app->user->identity->money, ['class' => 'account-money']),
                        [
                            'class' =>  'address-data'
                        ]),
                    [
                        'class' =>  'semi-font'
                    ])?>
            </div>
            <div class="user-account personal-discount">
                <div class="myriad">
                    <i class="icon icon-your-discont"></i>  Ваша скидка
                </div>
                <div class="semi-font">
                    <div class="address-data">
                        <div class="address">
                            Дисконтная карта - 2% на все товары на сайте
                        </div>
                        <div class="address">
                            Персональная скидка - 15% на <a>избранные</a> категории
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if((time() - strtotime(\Yii::$app->user->identity->giveFeedbackClosed)) > 3600){
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