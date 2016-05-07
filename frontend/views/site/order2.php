<?php

use common\helpers\Formatter;
use yii\jui\Accordion;
use yii\bootstrap\Html;

$this->registerMetaTag(['name' => 'robots', 'content' => 'noindex, follow'], 'robots');

$js = <<<JS

var page = 0;

$(".goToPage").on(hasTouch ? 'touchend' : 'click', function(e){
    page = parseInt(e.currentTarget.getAttribute('data-page'));
    $('#orderForm').yiiActiveForm('validateAttribute', 'orderform-customername');
    $('#orderForm').yiiActiveForm('validateAttribute', 'orderform-customersurname');
    $('#orderForm').yiiActiveForm('validateAttribute', 'orderform-deliverycity');
    $('#orderForm').yiiActiveForm('validateAttribute', 'orderform-deliveryregion');
    $('#orderForm').yiiActiveForm('validateAttribute', 'orderform-customeremail');
});

$('#orderForm').on('afterValidateAttribute', function(e, attr){
    if(attr.id == 'orderform-customeremail'){
        if(page == 1 && $('#orderForm .content-data-body-first').find('.has-error').length > 0){
            return false;
        }
        $('#accordion').accordion('option', 'active', page);
    }
});

$('#orderForm').on('beforeSubmit', function(){
    $('.load').append('<img src="img/site/jquery-preloader.gif" alt=Загрузка..." id="loading">');
});

JS;

$this->registerJs($js);

?>
<head>
    <script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
</head>
<script type="text/javascript">
    var kyiv_map;
    ymaps.ready(function(){
        kyiv_map = new ymaps.Map("map", {
            center: [50.47, 30.52],
            zoom: 8,
            // Изначально на карте есть только ползунок масштаба
            // и кнопка полноэкранного режима
            controls: ["zoomControl"]
        });
    });
</script>
    <script type="text/javascript">
    $(document).ready(function() { // вся мaгия пoсле зaгрузки стрaницы
        $('span#go').click( function(event){ // лoвим клик пo ссылки с id="go"
            event.preventDefault(); // выключaем стaндaртную рoль элементa
            $('body').css('overflow', 'hidden'); // выключаем скролл
            $('#overlay').fadeIn(400, // снaчaлa плaвнo пoкaзывaем темную пoдлoжку
                function(){ // пoсле выпoлнения предъидущей aнимaции
                    $('#modal_form')
                        .css('display', 'block') // убирaем у мoдaльнoгo oкнa display: none;
                        .animate({opacity: 1, top: '5%'}, 200); // плaвнo прибaвляем прoзрaчнoсть oднoвременнo сo съезжaнием вниз
                });
        });
        /* Зaкрытие мoдaльнoгo oкнa, тут делaем тo же сaмoе нo в oбрaтнoм пoрядке */
        $('#modal_close, #overlay').click( function(){ // лoвим клик пo крестику или пoдлoжке
            $('body').css('overflow', 'auto'); // включаем скролл
            $('#modal_form')
                .animate({opacity: 0, top: '45%'}, 200,  // плaвнo меняем прoзрaчнoсть нa 0 и oднoвременнo двигaем oкнo вверх
                    function(){ // пoсле aнимaции
                        $(this).css('display', 'none'); // делaем ему display: none;
                        $('#overlay').fadeOut(400); // скрывaем пoдлoжку
                    }
                );
        });
    });
        //TODO: что это за скрипт?
</script>
<script type="text/javascript">
    $(document).ready(function () {
        var offset = $('.ordering').offset();
        var topPadding = 10;
        $(window).scroll(function(){
            if($(window).scrollTop() > offset.top){
                $('.ordering').stop().animate({marginTop: $(window).scrollTop() - offset.top + topPadding});
            }else{
                $('.ordering').stop().animate({marginTop: 5});
            }
        });
    });
    //TODO: что это за скрипт?
</script>
<?php
$form = \yii\bootstrap\ActiveForm::begin([
    'id'            =>  'orderForm',
    'fieldConfig'   => [
        'template' => "{label}\n<div class=\"inputField\">\n{input}\n{hint}\n{error}\n</div>",
    ],
    'enableAjaxValidation' => false,
]);
?>
<div class="content">
    <div id="modal_form"><!-- Сaмo oкнo -->
        <span id="modal_close">
            <a>
                <div class="close-map"></div>
            </a>
        </span> <!-- Кнoпкa зaкрыть -->
        <div id="ymap">
            <div class="department-on-map">
                <div class="department-logo">
                </div>
                dfdsfdf
            </div>
            <div id="map">
            </div>
        </div>
    </div>
    <div id="overlay"></div><!-- Пoдлoжкa -->
    <div class="order-head">
        <div class="order-logo">
        </div>
        <div class="order-call-phone">
            <div class="phone">

            </div>
            (044) 232 82 20
        </div>
        <div class="ordering-title">
            <?=\Yii::t('shop', 'Оформление заказа')?>
        </div>
    </div>
    <div class="order-body">
        <div class="content-data">
        <?php
        echo Accordion::widget([
            'items' => [
                [
                    'header'    => Html::tag('div', Html::tag('span', 'Контактные данные').Html::button('редактировать', [
			                'class' =>  'btn btn-link goToPage',
			                'data-page'    =>  0,
                        ]),
                        ['class' =>  'content-data-first_1']
                    ),
                    'content' => $this->render('_order_item_content', [
                        'form'  =>  $form,
                        'model' =>  $model
                    ]),
                    'headerOptions' => [
                        'tag'   =>  'div',
	                    'onclick' => 'return false;'
                    ],
                ],
                [
                    'header' => Html::tag('div', Html::tag('span', 'Доставка и Оплата'), [
                        'class' =>  'content-data-first_1',

                    ]),

                    'headerOptions' => ['tag' => 'div',

                    ],
                    'content' => $this->render('_order_item_content_second', [
                        'form'  =>  $form,
                        'model' =>  $model,
                        'class'    =>  'collapse',
		                'domainConfiguration'      =>  $domainConfiguration
                    ]),
                    'options' => ['tag' => 'div'],
                ],
            ],
            'options' => [
                'tag'   =>  'div',
	            'id'    =>  'accordion'
            ],
            'itemOptions' => ['tag' => 'div'],
            'headerOptions' => ['tag' => 'div'],
            'clientOptions' => ['collapsible' => false, 'icons' => false, 'heightStyle' => 'content', 'event' => false],
        ]);?>
            </div>

        <div class="content-ordering">
            <div class="ordering">
                <div class="load">
                    <div class="ordering-body">
                        <div class="semi-bold"><?=\Yii::t('shop', 'Итого')?></div>
                        <div class="ordering-body-items">
                        <div class="ordering-body-items-discount">
                            <div class="all-price">
                                <?=\Yii::t('shop', '{n, number} {n, plural, one{товар} few{товара} many{товаров} other{товар}} на сумму', ['n' => \Yii::$app->cart->itemsCount])?>
                                <div class="bold">
                                    <span class="amount"><?=Formatter::getFormattedPrice(\Yii::$app->cart->cartSumWithoutDiscount)?></span>
                                </div>
                            </div>
                            <div class="price action-discount">
                                <?=\Yii::t('shop', 'Сумма скидки по акции')?>
                                <div class="bold">
	                                <span class="action-discount-amount"><?=Formatter::getFormattedPrice(\Yii::$app->cart->cartSumm - \Yii::$app->cart->cartSumWithoutDiscount, true)?></span>
                                </div>
                            </div>
	                        <div class="price card-discount">
		                        <?=\Yii::t('shop', 'Скидка по карте')?> (<span class="card-discount-percent"><?=((empty($customer) || empty($customer->cardNumber) || empty($customer->discount) || empty(\Yii::$app->cart->cartSumNotDiscounted)) ? 0 : '-'.$customer->discount)?>%</span>)
		                        <div class="bold">
			                        <span class="card-discount-amount"><?=Formatter::getFormattedPrice(((empty($customer) || empty($customer->cardNumber) || empty($customer->discount) || empty(\Yii::$app->cart->cartSumNotDiscounted)) ? 0 : -\Yii::$app->cart->cartSumNotDiscounted / 100 * $customer->discount), true)?></span>
		                        </div>
	                        </div>
                            <div class="price commission">
                                <?=\Yii::t('shop', 'Коммиссия')?> (<span class="commission-percent"></span><span class="commission-static"></span><span class="currency"> <?=\Yii::$app->params['domainInfo']['currencyShortName']?></span>)
                                <div class="bold">
	                                <span class="commission-amount"></span><span class="currency"> <?=\Yii::$app->params['domainInfo']['currencyShortName']?></span>
                                </div>
                            </div>
                        </div>
                        <div class="ordering-body-items-price">
                            <?=Html::tag('div', \Yii::t('shop', 'Предварительная сумма к оплате'), ['class' => 'ordering-body-items-price-sum']),
                            Html::tag('span', '?', [
                                'data-toggle'       =>  'tooltip',
                                'title'             =>  \Yii::t('shop', 'Эта сумма может измениться, в случае если вдруг не будет товаров на складе'),
                                'class'             =>  'question-round-button',
                            ])?>
                            <div class="semi-bold">
                                <span class="total-amount"></span><span class="currency"> <?=\Yii::$app->params['domainInfo']['currencyShortName']?></span>
                            </div>
                        </div>
                        <div class="ordering-body-order-confirm">
                            <?=\Yii::t('shop', 'Стоимость доставки согласно <a>тарифам Новой почты</a>')?>
                        </div>
                        <div class="ordering-body-order-confirm-button">
                            <?php
                                echo Html::submitButton('Оформить заказ', [
                                    'class' =>  'button yellow-button large-button'
                                ]);
                            ?>
                        </div>
                        <div class="terms-of-use">
                            <?=\Yii::t('shop', 'Подтверждая заказ, я принимаю условия').' '.Html::a(\Yii::t('shop', 'пользовательского соглашения'))?>
                        </div>
                            <div class="edit-order"><a href="#modalCart"><?=\Yii::t('shop', 'Редактировать заказ')?></a></div>
                            <div class="promotional-code">
                                <?=$form->field($model, 'promoCode')->widget(\kartik\editable\Editable::className(), [
                                    'valueIfNull'   =>  \Yii::t('shop', 'Ввести промокод')
                                ])->label(false).
                                Html::tag('span', '?', [
                                    'data-toggle'   =>  'popover',
                                    'data-content'  =>  \Yii::t('shop', 'Если у вас есть промокод от нас (обычно его можно получить в спаме на почту), вы можете ввести его здесь, и получить скидку. Скидка не суммируется с другими скидками.'),
                                    'data-title'    =>  \Yii::t('shop', 'Промокод'),
                                    'class'         =>  'question-round-button',
                                ])?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $form->end(); ?>
