<?php

use yii\jui\Accordion;
use yii\bootstrap\Html;

$css = <<<'STYLE'



.content{
    width: 100%;
    max-width: 1050px;
    margin: 0 auto;
    min-width: 880px;
}

.content-data-body label{
    font-weight: normal;
}

.order-head{
    height: 200px;
    width: 100%;
    font-family: OpenSans-Semibold;
    font-size: 32px;
    color: #40403e;
    padding-bottom: 25px;
    border-bottom: 4px solid #d3e8f9;
    padding-top: 35px;
}

.order-logo{
    width: 50%;
    background-repeat: no-repeat;
    height: 60px;
    background-image: url(img/site/logo.png);
    margin-bottom: 30px;
}

.order-call-phone{
    float: right;
    font-size: 20px;
    margin-top: -70px;
}

.ordering-title{
    width: 100%;
}

.order-body{
    width: 100%;
    padding-top: 25px;
}

.content-data{
    float: left;
    width: 55%;
    min-width: 470px;
}

.content-data-title{
    margin-bottom: 40px;
    width:100%;
    height: 30px;
}
.content-data-first .content-data-title{
    text-shadow: 0px 0px 1px #c6c6c6;
}

.content-data-second .content-data-title{
    text-shadow: 0px 0px 1px #e5e5e5;
}

.round-button {
	width:30px;
    float: left;
}

.content-data-first .content-data-title-img{
    width: 30px;
    height:0;
	padding-bottom: 100%;
    border-radius: 50%;
	border:px solid #cfdcec;
    overflow:hidden;
    background: #29a1e0 ;
    box-shadow: 0px 2px 1px #098fd5, #4CCCF3 0px 0px 6px 2px inset;
}

.content-data-second .content-data-title-img{
    width: 30px;
    height:0;
	padding-bottom: 100%;
    border-radius: 50%;
    overflow:hidden;
    background: #d4d4d4 ;
}

.round-button a {
    display:block;
	float:left;
	width:100%;
	padding-top:50%;
    padding-bottom:50%;
    line-height: 1;
	margin-top:-0.5em;
    text-align: center;
    text-align: -webkit-center;
    text-align: -moz-center;
    text-align: -o-center;
	color:#e2eaf3;
    font-family:OpenSans;
    font-size: 18px;
    text-decoration:none;
    cursor: pointer;
}

.contacy-data-title-buttom-border{
    height: 30px;
    float: left;
    margin-left: 10px;
    border-bottom: 1px solid #d8d8d8;
    width: 80%;
    margin-right: 30px;
}

.content-data-title-text{
    float: left;
    height: 30px;
    font-size: 22px;
    font-family: OpenSans-SemiBold;
    color: #40403e;
}

.content-data-second .content-data-title-text{
    float: left;
    height: 30px;
    font-size: 22px;
    font-family: OpenSans-SemiBold;
    color: #d4d4d4;
}

.content-data-title-edit{
    float: right;
    height: 30px;
}

.content-data-title-edit a{
    cursor: pointer;
    vertical-align: -webkit-baseline-middle;
    color: #3e77aa;
    font-size: 14px;
}

.content-data .control-label{
    float: left;
    /* padding-left: 40px; */
    padding-top: 6px;
    margin-left: 0px;
    width: 100px;
}

.content-data .form-control{
    float: left;
    width: 280px;
    -webkit-box-shadow: inset 0px 0px 3px 2px #f4f4f4;
    box-shadow: inset 0px 0px 3px 2px #f4f4f4;
}

.form-control{
    border: 1px solid #e4e4e4;
}

.content-data-body-first .form-group{
    height: 40px;
}

.content-data .form-group{
    margin-bottom: 10px;
}

.content-data-body{
    font-weight: normal;

}
.content-data-body-first{
    padding-bottom: 30px;
    margin-left: ;
    text-shadow: 0px 0px 1px #bdbdbd;
}

.content-data-body-first label{
    color: #282828;
}

.content-data-body-second{
    padding-bottom: 30px;
    margin-left: 40px;
    text-shadow: 0px 0px 1px #9db5cf;
}

.content-data-body-third{
    padding-bottom: 30px;
    margin-left: 40px;
    text-shadow: 0px 0px 1px #9db5cf;
    margin-bottom: 50px;
}

.content-data-body-third .form-group{
    height: 100%;
}

.ui-corner-all .content-data-first{
    display:;
}

.content-data-body-first .next-button{
    width: 100%;
    height:40px;
}

.next-button button{
    float: left !important;
}

.button{
    margin-left: 100px;
    height: 40px;
    width: 155px;
    background: #ffd600; /* For browsers that do not support gradients */
    background: -webkit-linear-gradient(#ffd600, #f9b519); /* For Safari 5.1 to 6.0 */
    background: -o-linear-gradient(#ffd600, #f9b519); /* For Opera 11.1 to 12.0 */
    background: -moz-linear-gradient(#ffd600, #f9b519); /* For Firefox 3.6 to 15 */
    background: linear-gradient(#ffd600, #f9b519); /* Standard syntax */
    box-shadow: 0px 2px 1px 1px #a1a1a1;
    border:solid 0px #ff00ff;
    -moz-border-radius: 5px;
    -webkit-border-radius:5px;
    border-radius:5px;
    border-top: 1px solid #fdff27;
}

.ordering-body-order-confirm-button{
    width: 100%;
    text-align: center;
    padding-bottom: 20px;
}

.ordering .button{
    width: 285px;
    margin-left: 0px;
}

.content-data-body-department{
    height:40px;
    padding: 10px;
}

.content-data-body-address{
    height: ;
    padding: 10px;
}

.content-data-body-stock{
    background: #fff9e7;
    border: 1px solid #f1e9d3;
    width: 370px;
    height: 100px;
    -moz-border-radius: 5px;
    -webkit-border-radius:5px;
    border-radius:5px;
    margin-top: 20px;
    margin-bottom: 30px;
    padding: 10px;
    text-shadow: 1px 0px 1px #bdb9ac;
}

.content-data-body-stock .work-time{
    color: #a5976c;
    text-shadow: 1px 0px 1px #ded5bc;
}

.content-ordering{
    width: 45%;
    float: left;
    min-width: 380px;
    padding-top: 25px;
    padding-right: 10px;
    padding-left: 35px;
}

.ordering{
position: absolute;
    width: 100%;
    float: right;
    max-width: 410px;
    min-width: 380px;
    border: 1px solid #f1e9d3;
    background-image: url(img/site/ordering-background.png);
    -moz-border-radius: 5px;
    -webkit-border-radius:5px;
    border-radius:5px;
}

.ordering-body{
    padding-top: 20px;
    padding-left: 25px;
    padding-right: 30px;
}

.ordering-body .semi-bold{
    padding-bottom: 30px;
    color: #282828;
    text-shadow: 0px 0px 1px #a9a59b;
}

.ordering-body-items .bold{
    float: right;
    padding-bottom: 0px;
    text-align: right;
}

.ordering-body-items-discount{
    border-bottom: 1px solid #c1b9a0;
    padding-bottom: 10px;
    text-shadow: 1px 0 1px #d2cdbe;
}

.ordering-body-items-discount .all-price{
    padding-bottom: 30px;
}

.ordering-body-items-discount .price{
    padding-bottom: 10px;
}

.ordering-body-items-price{
    padding-top: 20px;
    text-shadow: 1px 0 1px #d2cdbe;
    width: 100%;
    padding-left: 5px;
    height: 80px;
    padding-bottom: 20px;
    border-bottom: 1px solid #c1b9a0;
}

.ordering-body-items-price-sum{
    height: 100%;
    float: left;
    width: 130px;
}

.ordering-body-order-confirm{
    padding-top: 25px;
    padding-bottom: 25px;
    text-align: center;
    font-size: 13px;
}

.ordering .question{
    float: left;
    padding-top: 3px;
    height: 100%;
}

.ordering-body-items-price .semi-bold{
    float: right;
    font-size: 28px;
}

.question .round-button {
    width: 15px;
    float: left;
    height: 15px;
}

.question .content-data-title-img{
    width: 15px;
    padding-bottom: 100%;
    border-radius: 100%;
    background: #03a7d4;
}


.question .round-button a {
    display: block;
    font-family: sans-serif;
    font-size: 12px;
    text-decoration: none;
}

.all-price .bold{
    border-bottom: 1px solid #c1b9a0;
}

.Terms-of-use{
    color: #a5976c;
    text-align: -webkit-center;
    text-align: -moz-center;
    text-align: -o-center;
}

.Terms-of-use a{
    color: #a5976c;
    cursor: pointer;
}

.Terms-of-use .text{
    text-align: center;
    width: 220px;
    font-size: 11px;
    padding-bottom: 25px;
}

.br{
    padding-left: 20px;
    padding-right: 5px;
    padding-bottom: 3px;
}

.text-align-center{
    text-align: -webkit-center;
    text-align: -moz-center;
    text-align: -o-center;
    padding-bottom: 10px;
}


.text-align-center a{
    cursor: pointer;
}

.promotional-code{
    width: 150px;
    height: 20px;
    margin-left: 6%;
}

.promotional-code .form-group{
    float: left;
}

.promotional-code a{
    float: left;

}

.promotional-code .question{
    padding-top: 5px;
    padding-left: 5px;
}

.ui-state-default{
    border: none;
    background: none;
}

.ui-widget-content{
    border: none;
}

.content-data-first_1{
    margin-left: 40px;
    font-size: 22px;
    color: #d4d4d4;
    border-bottom: 1px solid #d8d8d8;
}

.content-data-first_1 span{
    font-family: OpenSans-SemiBold;
}

.ui-accordion-header:before{
    background: #d4d4d4;
    color: #fff
}

.ui-accordion-header .content-data-first_1:before{
    color: #fff
}

.ui-accordion-header:before{
    height: 30px;
    width: 30px;
    line-height: 30px;
    border-radius: 50%;
    overflow: hidden;
    display: inline-block;
    float: left;
    text-align: center;
    font-family: OpenSans;
    font-size: 18px;
    text-decoration: none;
    cursor: pointer;
    clear: both
}

.ui-accordion-header#ui-id-1:before{
    content: '1';
}

.ui-accordion-header#ui-id-3:before{
    content: '2';
}

.ui-accordion-header-active:before{
    background: #29a1e0;
    color: #e2eaf3;
    box-shadow: 0px 2px 1px #098fd5, #4CCCF3 0px 0px 6px 2px inset;

}

.ui-state-default .content-data-first_1 a{
    cursor: pointer;
    color: #3e77aa;
    font-size: 14px;
    float: right;
    margin-right: 10px;
    line-height: 30px;
}

.ui-state-default.ui-state-active .content-data-first_1 a{
    display: none;
}

.ui-accordion-header-active .btn {
    display: none;

}
.ui-state-default.ui-state-active .content-data-first_1{
    color: #000;
}

.ui-state-default .content-data-first_1 a:hover{
    text-decoration: underline;
}

.control-label{
    font-family: OpenSans;
    font-weight: normal;
}

.phone{
    background-image: url(img/site/phone.png);
    background-repeat: no-repeat;
    height: 20px;
    width: 20px;
    float: left;
    margin: 5px;
}

.content-data-body-delivery-type{
    height: ;
    padding-bottom: 15px;
}

.content-data-body-delivery-type input[type="radio"] {
    display: none;
    float: left;
}

.content-data-body-delivery-type input[type="radio"]:checked + label{
    -moz-border-radius: 5px;
    -webkit-border-radius:5px;
    border-radius:5px;
    background: #d3e8f9;
    border: 1px solid #bdddf7;
}

.content-data-body-delivery-type label {
    display:inline-block;
    padding:4px 11px;
    float: left;
    cursor: pointer;
    margin-right: 10px;
    color: #3e77aa;
    font-size: 14px;
    font-weight: normal;
}

#ui-id-2{
    height:370px;
}

#ui-id-4{
    height: 500px%;
}
.nav-tabs{
    border: none;
}

#w2-tab1 label{
    color: #282828;
}

.content-data-first_1 .ui-widget button{
    font-size: 20px;
}
.btn{
    font-size: 14px !important;
    font-weight: normal !important;
}

.ui-widget button{
    float: right;
}

.ui-accordion .ui-accordion-content{
    padding-left: 50px;
}

#orderform-paymenttype input[type="radio"]{
    display: none;
}


#orderform-paymenttype i{
    margin-right: 15px;
    display: inline-block;
    width: 15px;
    height: 15px;
    border-radius: 8px;
    float: left;
    background-color: #ffffff;
    -moz-box-shadow:    inset 0px 0px 1px 3px #e4e4e4;
    -webkit-box-shadow: inset 0px 0px 1px 3px #e4e4e4;
    box-shadow: inset 0px 0px 1px 3px #e4e4e4;
}

#orderform-paymenttype input[type="radio"]:checked + label i{
    width: 15px;
    height: 15px;
    border-radius: 8px;
    background-color: #3e77aa;
}

#orderform-paymenttype .tab{
    padding-bottom: 25px;
}

#orderform-paymenttype label{
    font-size: 14px;
    margin-bottom: 0px;
    font-weight: normal;
    padding-left: 15px;
    float: left;
    padding-right: 10px;
}

.add-comment a{
    color: #337ab7;
}

.payment-type{
    border-bottom: 1px solid #d8d8d8;
    font-family: OpenSans-SemiBold;
    font-size: 22px;
    margin-bottom: 20px;
}

.payment-type-text{
        float: left;
    margin-top: -3px;
}

#ui-id-2 p{
    padding-left: 100px;
    padding-top: 34px;
}

#ui-id-4 p{
    padding-left: 110px;
    padding-top: 34px;
}

#ymap{
    width: 1050px;
   }

#map{
    height:700px;
    display: ;
    min-width: 800px;
    max-width: 800px;
    float: right;
}

.department-on-map{
    width: 250px;
    float: left;
    height: 700px;
    background: white;
}

.department-logo{
    background-image: url(img/site/department-logo.png);
    width: 250px;
    height: 80px;
}

#overlay {
	z-index:3; /* пoдлoжкa дoлжнa быть выше слoев элементoв сaйтa, нo ниже слoя мoдaльнoгo oкнa */
	position:fixed; /* всегдa перекрывaет весь сaйт */
	background-color:#000; /* чернaя */
	opacity:0.5; /* нo немнoгo прoзрaчнa */
	-moz-opacity:0.8; /* фикс прозрачности для старых браузеров */
	filter:alpha(opacity=80);
	width:100%;
	height:100%; /* рaзмерoм вo весь экрaн */
	top:0; /* сверху и слевa 0, oбязaтельные свoйствa! */
	left:0;
	cursor:pointer;
	display:none; /* в oбычнoм сoстoянии её нет) */
}

#modal_form {
	position: fixed; /* чтoбы oкнo былo в видимoй зoне в любoм месте */
	display: none; /* в oбычнoм сoстoянии oкнa не дoлжнo быть */
	opacity: 0; /* пoлнoстью прoзрaчнo для aнимирoвaния */
	z-index: 5; /* oкнo дoлжнo быть нaибoлее бoльшем слoе */
}

#modal_form #modal_close {
    background: white;
	position: absolute;
	top: 10px;
	right: 10px;
	cursor: pointer;
	display: block;
	z-index: inherit;
	width: 38px;
    height: 38px;
    border-radius: 50%;
    background-color: #FFF;
    border: 5px solid #FFF;
}

#go {
    color: #3E77AA !important;
    border-bottom: 1px dotted;
    text-decoration: none;
    display: block;
float: right;
}

.map-icon{
    background-image: url(img/site/map-icon.png);
    background-repeat: no-repeat;
    width: 16px;
height: 16px;
float: left;
}

#modal_close{
    border: 5px solid #FFF;

}

.close-map{
    background-image: url(img/site/close.png);
    background-repeat: no-repeat;
    width: 28px;
    height: 28px;
}
.close-map:hover{
    background-image: url(img/site/close-on-hover.png);
    background-repeat: no-repeat;
    width: 28px;
    height: 28px;
}

STYLE;

$this->registerCss($css);

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
        $('a#go').click( function(event){ // лoвим клик пo ссылки с id="go"
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
        var topPadding = 0;
        $(window).scroll(function() {
            if ($(window).scrollTop() > offset.top) {
                $('.ordering').stop().animate({marginTop: $(window).scrollTop() - offset.top + topPadding});
            }
            else {
                $('.ordering').stop().animate({marginTop: 0});
            }
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
                        'class'    =>  'collapse'
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
        </div>
        <div class="content-ordering">
            <div class="ordering">
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
                                 'class' =>  'button'
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



<?php $form->end(); ?>
