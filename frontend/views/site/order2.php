<?php

$form = new \yii\widgets\ActiveForm();

$css = <<<'STYLE'

#tab2, #tab3 {position: fixed;  }

.menu1 > a,
.menu1 #tab2:target ~ a:nth-of-type(1),
.menu1 #tab3:target ~ a:nth-of-type(1),
.menu1 > div {
    padding: 5px;
    padding-left: 8px;
    padding-right: 8px;
    border: none;
    margin-right: 10px;
    background: none;
}

.menu1 > a {

    background: none;
    color: #3e77aa;
    border: none;
    text-decoration: none;
}

#tab2,
#tab3,
.menu1 > div,
.menu1 #tab2:target ~ div:nth-of-type(1),
.menu1 #tab3:target ~ div:nth-of-type(1) {
    display: none;
    color: ;
}

.menu1 > div:nth-of-type(1),
.menu1 #tab2:target ~ div:nth-of-type(2),
.menu1 #tab3:target ~ div:nth-of-type(3) {
    display: block;
}

.menu1 > a:nth-of-type(1),
.menu1 #tab2:target ~ a:nth-of-type(2),
.menu1 #tab3:target ~ a:nth-of-type(3) {
    -moz-border-radius: 5px;
    -webkit-border-radius:5px;
    border-radius:5px;
    background: #d3e8f9;
    border: 1px solid #bdddf7;
}

.shipping > div, .shipping > input {
    display: none;
}
.shipping label {
    padding: 5px;
    color: #3e77aa;
    cursor: pointer;
    position: relative;
}
.shipping input[type="radio"]:checked + label {
    -moz-border-radius: 5px;
    -webkit-border-radius:5px;
    border-radius:5px;
    background: #d3e8f9;
    border: 1px solid #bdddf7;
}
.shipping > input:nth-of-type(1):checked ~ div:nth-of-type(1),
.shipping > input:nth-of-type(2):checked ~ div:nth-of-type(2),
.shipping > input:nth-of-type(3):checked ~ div:nth-of-type(3) {
    display: block;
    padding: 5px;
}

.content{
width: 100%;
    max-width: 1250px;
    margin: 0 auto;
    min-width: 820px;
}

.content-data-body label{
    font-weight: normal;
}

.order-head{
    width: 100%;
    font-family: OpenSans-Semibold;
    font-size: 32px;
    color: #40403e;
    padding-bottom: 25px;
    border-bottom: 4px solid #d3e8f9;

}

.order-logo{
    width: 50%;
    background-repeat: no-repeat;
    background-image: url(https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcSCZwIQ2i-Bz3NyE7NBDEVxzwTu5bpbr4YBaQ1gvaFmnHly-U0W);
}

.order-call-phone{
    float: right;
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
    width: 60%;
    min-width: 470px;
    padding-left: 10px;
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
    text-align:center;
	color:#e2eaf3;
    font-family:OpenSans;
    font-size: 18px;

    text-decoration:none;
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
}

.content-data .form-control{
    float: right;


    width: 280px;
    border: 1px solid #e4e4e4;
    -webkit-box-shadow: inset 0px 0px 3px 2px #f4f4f4;
    box-shadow: inset 0px 0px 3px 2px #f4f4f4;
}

.content-data .form-group{
    height: 40px;
    width: 375px;
    margin-bottom: 10px;
}

.content-data-body{
    font-weight: normal;

}
.content-data-body-first{
    padding-bottom: 30px;

        margin-left: 40px;

            text-shadow: 0px 0px 1px #bdbdbd;
}

.content-data-body-second{
    padding-bottom: 30px;
        margin-left: 40px;
text-shadow: 0px 0px 1px #9db5cf;
}

.content-data-second .content-data-body-second{
    display:none;
}

.content-data-body-first .next-button{
    width: 100%;
    height:40px;
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
    width: 40%;
    float: left;
}

.ordering{
    width: 100%;
    float: left;
min-width: 380px;
    height: 545px;
    border: 1px solid #f1e9d3;
    background-image: url(img/site/ordering-background.png);
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
}

.Terms-of-use a{
    color: #a5976c;
    cursor: pointer;
}

.Terms-of-use .text{
text-align: center;
    width: 220px;
    font-size: 11px;
    padding-bottom: 30px;
}

.br{
    padding-left: 20px;
    padding-right: 5px;
    padding-bottom: 3px;
}

STYLE;

$this->registerCss($css);
?>

<div class="content">
    <div class="order-head">
        <div class="order-logo">
        </div>
        <div class="order-call-phone">
            (044) 232 82 20
        </div>
        <div class="ordering-title">
            Оформление заказа
        </div>
    </div>
    <div class="order-body">
        <div class="content-data">
            <div class="content-data-first">
                <div class="content-data-title">
                    <div class="round-button">
                        <div class="content-data-title-img">
                            <a href="" class="round-button">1</a>
                        </div>
                    </div>
                    <div class="contacy-data-title-buttom-border">
                        <div class="content-data-title-text">
                            Контактные данные
                        </div>
                        <div class="content-data-title-edit">
                            <a>редактировать</a>
                        </div>
                    </div>
                </div>
                <div class="content-data-body">
                    <div class="content-data-body-first">
                        <?= $form->field($model, 'customerName'),
                            $form->field($model, 'customerSurname'),
                            $form->field($model, 'deliveryCity'),
                            $form->field($model, 'customerEmail')?>
                        <div class="next-button">
                        <?php
                        echo \yii\helpers\Html::button('Далее', [
                                'class' =>  'button',
                                'type'  =>  'submit'
                            ]);
                        ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-data-first">
                <div class="content-data-title">
                    <div class="round-button">
                        <div class="content-data-title-img">
                            <a href="" class="round-button">2</a>
                        </div>
                    </div>
                    <div class="contacy-data-title-buttom-border">
                        <div class="content-data-title-text">
                            Доставка
                        </div>
                    </div>
                </div>
                <div class="content-data-body">
                    <div class="content-data-body-second">
                        <div class="content-data-body-delivery-type">
                            <?=$form->field($model, 'deliveryType')->radioList(\common\models\DeliveryTypes::getDeliveryTypes())->label(false)?>
                            <?=\yii\bootstrap\Tabs::widget([
                                'headerOptions' =>  [
                                    'style' =>  'display: none'
                                ],
                                'items' =>  [
                                    [
                                        'content'   =>  '<div class="content-data-body-department">Отделение:</div>',
                                        'label'     =>  'Новая почта',
                                        'id'        =>  'newPost'
                                    ],
                                    [
                                        'content'   =>  '<div class="content-data-body-address">Мои адреса:</div>',
                                        'label'     =>  'Адресная доставка',
                                        'id'        =>  'delivery'
                                    ],
                                    [
                                        'content'   =>  '<div>
                                <div class="content-data-body-stock">


                                    <div class="semi-bold">Наш склад находится по адресу:</div>
                                    г. Киев, ул. Электротехническая, 2
                                    <div class="work-time">
                                        Время работы с 9:00 до 17:00
                                    </div>
                                    <div class="work-time">
                                    все дни кроме понедельника
                                    </div>
                                </div>
                                </div>',
                                        'label'     =>  'Самовывоз',
                                        'id'        =>  'post2'
                                    ],
                                ]
                            ])?>
                            <div class="menu1">
                                <br id="tab2"/><br id="tab3"/>
                                <a href="#tab1">Новая почта</a><a href="#tab2">Адресная доставка</a><a href="#tab3">Самовывоз</a>



                            </div>
                        </div>
                        <?=$form->field($model, 'anotherReceiver')->radioList([
                            '0' =>  'Отправлять на меня',
                            '1' =>  'Будет получать другой человек'
                        ])->label(false)?>
                        <div class="shipping">

                            <input type="radio" name="odin" checked="checked" id="vkl1"/>
                            <label for="vkl1">
                                Отправлять на меня
                            </label>
                            <input type="radio" name="odin" id="vkl2"/>
                            <label for="vkl2">
                                Будет получать другой человек
                            </label>
                            <div></div>
                            <div class="">
                            <?= /*$form->field($model, 'deliveryType')->dropDownList(\common\models\DeliveryTypes::getDeliveryTypes()),*/
                                $form->field($model, 'customerName'),
                                $form->field($model, 'customerSurname'),
                                $form->field($model, 'customerPhone')
                                //$form->field($model, 'deliveryInfo')?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-data-first">
                <div class="content-data-title">
                    <div class="round-button">
                        <div class="content-data-title-img">
                            <a href="" class="round-button">3</a>
                        </div>
                    </div>
                    <div class="contacy-data-title-buttom-border">
                        <div class="content-data-title-text">
                            Оплата
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-ordering">
            <div class="ordering">
                <div class="ordering-body">
                    <div class="semi-bold">Итого</div>
                    <div class="ordering-body-items">
                    <div class="ordering-body-items-discount">
                        <div class="all-price">
                            253 товара на сумму
                            <div class="bold">
                                <div class="br">
                                    25 800 грн.
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
                                    <a href="" class="round-button">?</a>
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
                        <div class="" style="text-align: center;"><a>Редактировать заказ</a>

                        </div>
                        <div style="text-align: -webkit-center;">
                            <div class="" style="width: 140px;height: 20px;"><a style="float: left; padding-right: 5px;">Ввести промокод</a>
                                <div class="question">
                                    <div class="round-button">
                                        <div class="content-data-title-img">
                                            <a href="" class="round-button">?</a>
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
