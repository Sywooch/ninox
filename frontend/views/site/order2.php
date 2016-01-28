<?php

use yii\jui\Accordion;
use yii\bootstrap\Html;

$js = <<<'SCRIPT'
$(".goToPage").on(hasTouch ? 'touchend' : 'click', function(e){

    var page = e.currentTarget.getAttribute('data-page');

    if(page == 1 && goToPage1() == 'not validated'){
        return false;
    }

	$('#accordion').accordion('option', 'active', parseInt(e.currentTarget.getAttribute('data-page')));
});

var goToPage1 = function(){
    //TODO: насчёт валидации формы

    $('#orderForm').data('yiiActiveForm').submitting = true;

    $('#orderForm').yiiActiveForm('validate')

    $('#orderForm').data('yiiActiveForm').submitting = false;

    setTimeout(200);

    if($('#orderForm .content-data-body-first').find('.has-error').length > 0) {
        return 'validated';
    }

    return 'not validated';
}

SCRIPT;

$this->registerJs($js);

$form = \yii\bootstrap\ActiveForm::begin([
    'id'            =>  'orderForm',
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"inputField\">\n{input}\n{hint}\n{error}\n</div>",
    ],
]);
?>
<head>
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"> </script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="http://code.jquery.com/jquery-latest.js"></script><!-- Прелоадер -->
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
</script>
<script type="text/javascript">
$(function() {
$('#submit').click(function() {
//Добавляем нашу картинку в <div  id="container">
    $('.load').append('<img src="img/site/jquery-preloader.gif" alt=Загрузка..." id="loading" />');
    //Передаем данные в файл ajax.php

    var customerName = $('#customerName').val();
    var customerSurname = $('#customerSurname').val();
    var deliveryCity = $('#deliveryCity').val();

    $.ajax({
    url: 'ajax.php',
    type: 'POST',
    data: '&customerName=' + customerName + '&customerSurname=' + customerSurname + '&deliveryCity=' + deliveryCity,

    success: function() {

    $('.load').animate({opacity:0.5},  function() {
        $('.load').css('', '');

    });
    }
    });
    return false;
    });
    });
    </script>
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
            Оформление заказа
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
                        <div class="semi-bold">Итого</div>
                        <div class="ordering-body-items">
                        <div class="ordering-body-items-discount">
                            <div class="all-price">
                                <?=\Yii::t('shop', '{n, number} {n, plural, one{товар} few{товара} many{товаров} other{товар}}', ['n' => \Yii::$app->cart->itemsCount])?> на сумму
                                <div class="bold">
                                    <div class="br">
                                        <?=\Yii::$app->cart->cartRealSumm?> <?=\Yii::$app->params['domainInfo']['currencyShortName']?>
                                    </div>
                                </div>
                            </div>
                            <div class="price">
                                Скидка по карте
                                <div class="bold">
                                    -200 грн.
                                </div>
                            </div>
                            <div class="price">
                                Сумма скидки по акции
                                <div class="bold">
                                    -4000 грн.
                                </div>
                            </div>
                            <div class="price">
                                Услуги банка (+1%)
                                <div class="bold">
                                    +13 грн.
                                </div>
                            </div>
                        </div>
                        <div class="ordering-body-items-price">
                            <div class="ordering-body-items-price-sum">Предварительная сумма к оплате</div>
                            <div class="question">
                                <div class="round-button">
                                    <div class="content-data-title-img">
                                        <?=Html::tag('a', '?', [
                                            'data-toggle'   =>  'tooltip',
                                            'data-title'    =>  'Эта сумма может измениться, в случае если вдруг не будет товаров на складе',
                                            'class'         =>  'round-button',
                                        ])?>
                                    </div>
                                </div>
                            </div>
                            <div class="semi-bold">
                                21 500 грн.
                            </div>
                        </div>
                        <div class="ordering-body-order-confirm">
                            Стоимость доставки согласно <a>тарифам Новой почты</a>
                        </div>
                        <div class="ordering-body-order-confirm-button">
                            <?php
                                echo \yii\helpers\Html::button('Оформить заказ', [
                                     'type'  =>  'submit',
                                     'class' =>  'yellow-button large-button',
                                     'id'    =>  'submit'
                                        ]);
                            ?>
                        </div>
                        <div class="Terms-of-use">
                            <div class="text">
                                Подтверждая заказ, я принимаю условия
                                <a>пользовательского соглашение</a>
                            </div>
                        </div>
                            <div class="text-align-center"><a href="#modalCart">Редактировать заказ</a>

                            </div>
                            <div class="text-align-center">
                                <div class="promotional-code">
                                    <?=$form->field($model, 'promoCode')->widget(\kartik\editable\Editable::className(), [
                                        'valueIfNull'   =>  'Ввести промокод'
                                    ])->label(false)?>
                                    <div class="question">
                                        <div class="round-button">
                                            <div class="content-data-title-img">
                                                <?=Html::tag('a', '?', [
                                                    'data-toggle'   =>  'popover',
                                                    'data-content'  =>  'Если у вас есть промокод от нас (обычно его можно получить в спаме на почту), вы можете ввести его здесь, и получить скидку. Скидка не суммируется с другими скидками.',
                                                    'data-title'    =>  'Промокод',
                                                    'class'         =>  'round-button',
                                                ])?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php $form->end(); ?>
