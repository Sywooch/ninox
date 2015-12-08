<?php

use bobroid\remodal\Remodal;

?>
<div class="content">
    <div class="menu">
        <?=\frontend\widgets\ListGroupMenu::widget([
            'items'    => [
                [
                    'label' =>  'Мой аккаунт',
                    'href'  =>  '/account'
                ],
                [
                    'label' =>  'Мои заказы',
                    'href'  =>  '/account/orders'
                ],
            ]
        ])?>
        Менюшка
    </div>
    <div class="user-data-content">
        <div class="user-account-data">
            <div class="pages">
                <div class="page">
                    <div class="user-account personal-data">
                        <div class="myriad">
                            <i class="icon icon-note"></i> Личные данные
                        </div>
                        <div class="semi-font data">
                            <div>
                                <div class="semi">
                                    Имя
                                </div>
                                <div class="personal-data">
                                    <?=\Yii::$app->user->identity->Company?>
                                </div>
                            </div>
                            <div>
                                <div class="semi">
                                    Телефон
                                </div>
                                <div  class="personal-data">
                                    <div class="personal-data">
                                        <?=\Yii::$app->user->identity->phone?>
                                    </div>
                                    <div>
                                        +380932521574
                                    </div>
                                </div>
                            </div>
                            <div>
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
                    <div class="user-account">
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
        <div class="reviews user-account">
            <div class="text">
                <div class="myriad">
                   Сделайте наш сервис еще лучше
                </div>
                <div class="semi-font">
                    Оставьте отзывы о купленных вами товарах
                </div>
                <div class="review">
                    <div class="items">
                        <div class="image">
                        </div>
                        <div class="write-review">
                            Написать отзыв
                        </div>
                    </div>
                    <div class="items">
                        <div class="image">
                        </div>
                        <div class="write-review">
                            Написать отзыв
                        </div>
                    </div>
                    <div class="items">
                        <div class="image">
                        </div>
                        <div class="write-review">
                            Написать отзыв
                        </div>
                    </div>
                    <div class="items">
                        <div class="image">
                        </div>
                        <div class="write-review">
                            Написать отзыв
                        </div>
                    </div>
                    <div class="items">
                        <div class="image">
                        </div>
                        <div class="write-review">
                            Написать отзыв
                        </div>
                    </div>
                    <div class="items">
                        <div class="image">
                        </div>
                        <div class="write-review">
                            Написать отзыв
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="my-orders">
            <div class="user-account box myriad">
                <i class="icon icon-box"></i> Мои заказы
            </div>
            <div class="orders">
                <div class="order order-waiting">
                    <i class="icon icon-arrow"></i>
                    <div class="myriad">
                        27227
                    </div>
                    <div class="data semi">
                        01.09.2015
                    </div>
                    <div class="payment semi">
                        Ожидается оплата
                    </div>
                    <div class="money semi">
                        254 798 грн.
                    </div>
                    <div class="print semi">
                        <i class="icon icon-print"></i>
                    </div>
                    <div class="history semi">
                        <a>История</a>
                    </div>
                    <div class="reorder semi">
                        <a>Повторить заказ</a>
                    </div>
                </div>
                <div class="order order-complete">
                    <i class="icon icon-arrow"></i>
                    <div class="myriad">
                        26587
                    </div>
                    <div class="data semi">
                        15.08.2015
                    </div>
                    <div class="payment semi">
                        Выполнен
                    </div>
                    <div class="money semi">
                        1300 грн.
                    </div>
                    <div class="print semi">
                        <i class="icon icon-print"></i>
                    </div>
                    <div class="history semi">
                        <a>История</a>
                    </div>
                    <div class="reorder semi">
                        <a>Повторить заказ</a>
                    </div>
                </div>
                <div class="order order-canceled">
                    <i class="icon icon-arrow"></i>
                    <div class="myriad">
                        26213
                    </div>
                    <div class="data semi">
                        03.08.2015
                    </div>
                    <div class="payment semi">
                        Отменен
                    </div>
                    <div class="money semi">
                        254 586 798 грн.
                    </div>
                    <div class="print semi">
                        <i class="icon icon-print"></i>
                    </div>
                    <div class="history semi">
                        <a>История</a>
                    </div>
                    <div class="reorder semi">
                        <a>Повторить заказ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


