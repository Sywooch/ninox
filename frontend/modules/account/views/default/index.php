<?php

use bobroid\remodal\Remodal;

?>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript">
function diplay_hide (reviews)
{
    if ($(reviews).css('display') == 'none')
    {
        $(reviews).animate({height: 'show'}, 500);
    }
    else
    {
        $(reviews).animate({height: 'hide'}, 500);
    }}
</script>
<div class="content">
    <div class="menu">
        <?=\frontend\widgets\ListGroupMenu::widget([
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
                    'href'  =>  '/account/123'
                ],
                [
                    'label' =>  'Ярмарка мастеров',
                    'href'  =>  '/account/mas'
                ],
            ]
        ])?>
    </div>
    <div class="user-data-content">
        <div class="user-account-data">
            <div class="pages">
                <div class="page">
                    <div class="user-account personal-data data">
                        <div class="myriad">
                            <i class="icon icon-note"></i> Личные данные
                        </div>
                        <div class="semi-font data">
                            <div class="name">
                                <div class="semi">
                                    Имя
                                </div>
                                <div class="personal-data">
                                    <?=\Yii::$app->user->identity->Company?>
                                </div>
                            </div>
                            <div class="phone">
                                <div class="semi">
                                    Телефон
                                </div>
                                <div class="">
                                    <div class="">
                                        <?=\Yii::$app->user->identity->phone?>
                                    </div>
                                    <div class="personal-data">
                                        <?=\Yii::$app->user->identity->Phone2?>
                                    </div>
                                </div>
                            </div>
                            <div class="email">
                                <div class="semi">
                                    Эл. почта
                                </div>
                                <div class="personal-data">
                                    <?=\Yii::$app->user->identity->email?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
            <div class="user-options">
                <div class="border">
                    <?=Remodal::widget([
                        'confirmButton'	=>	false,
                        'id'			=>	'editAccount',
                        'cancelButton'	=>	false,
                        'addRandomToID'	=>	false,
                        'content'		=>	$this->render('_account_edit_modal'),
                        'buttonOptions'	=>	[
                            'label'		=>	\Yii::t('shop', 'Редактировать')
                        ],
                    ]),
                    Remodal::widget([
                            'confirmButton'	=>	false,
                            'id'			=>	'changePassword',
                            'cancelButton'	=>	false,
                            'addRandomToID'	=>	false,
                            'content'		=>	$this->render('_change_password_modal'),
                            'buttonOptions'	=>	[
                                'label'		=>	\Yii::t('shop', 'Изменить пароль')
                            ],
                        ])?>
                    <a>Выход</a>
                </div>
            </div>
        </div>
        <div class="user-money-discount">
            <div class="user-account personal-discount">
                <div class="myriad">
                    <i class="icon icon-money-pig"></i> Личный счет
                </div>
                <div class="semi-font">
                    <div class="address-data">
                        <div class=" address">
                            На вашем счету:
                        </div>
                            <div class="  account-money">
                            <?=\Yii::$app->user->identity->money?>
                        </div>
                    </div>
                </div>
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
        <div id="reviews" class="reviews user-account">
            <div class="pull-right">
                <i onclick="diplay_hide('.reviews');return false;" class="icon icon-exit"></i>
            </div>
            <div class="text">
                <div class="review-tittle">
                    <div class="myriad">
                       Сделайте наш сервис еще лучше
                    </div>
                    <div class="semi-font">
                        Оставьте отзывы о купленных вами товарах
                    </div>
                </div>
                <div class="review">
                    <?=$this->render('_review_items', [
                        'review_item_image' =>  [
                            'image'   =>  'img/catalog/265efa88d5d8547c1b9d65f57ca003bdac2ffa911433839838.png',
                        ]
                    ])?>
                    <?=$this->render('_review_items', [
                        'review_item_image' =>  [
                            'image'   =>  'img/catalog/zont-avtomat-mo-007-1-sht-629084.jpg',
                        ]
                    ])?>
                    <?=$this->render('_review_items', [
                        'review_item_image' =>  [
                            'image'   =>  'img/site/sven.jpg',
                        ]
                    ])?>
                    <?=$this->render('_review_items', [
                        'review_item_image' =>  [
                            'image'   =>  'img/catalog/892fb5764422cfbeeb1da96bb02e9465b91bc7551431090634.png',
                        ]
                    ])?>
                    <?=$this->render('_review_items', [
                        'review_item_image' =>  [
                            'image'   =>  'img/blog/articles/gde-kupit-zakolku-297982.jpg',
                        ]
                    ])?>
                    <?=$this->render('_review_items', [
                        'review_item_image' =>  [
                            'image'   =>  'img/catalog/f107ffd8dd260383c57c457b17bef6a9d8493b6a1433513755.png',
                        ]
                    ])?>
                </div>
            </div>
        </div>
    </div>
</div>