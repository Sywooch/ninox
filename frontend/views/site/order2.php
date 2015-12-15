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

.order-body .button{
    background: red;
}

.contact-data{
    float: left;
    width:50%;
    padding-left: 10px;
}

.contact-data-title{
    margin-bottom: 40px;
    width:100%;
    height: 30px;
}

.round-button {
	width:30px;
    float: left;
}

.content-data-first .contact-data-title-img{
    width: 30px;
    height:0;
	padding-bottom: 100%;
    border-radius: 50%;
	border:px solid #cfdcec;
    overflow:hidden;
    background: #29a1e0 ;
    box-shadow: 0px 2px 1px #098fd5, #4CCCF3 0px 0px 6px 2px inset;
}

.content-data-second .contact-data-title-img{
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

.contact-data-title-text{
    float: left;
        height: 30px;
    font-size: 22px;
    font-family: OpenSans-SemiBold;
    color: #40403e;
}

.content-data-second .contact-data-title-text{
    float: left;
    height: 30px;
    font-size: 22px;
    font-family: OpenSans-SemiBold;
    color: #d4d4d4;
}

.contact-data-title-edit{
    float: right;
    height: 30px;
}

.contact-data-title-edit a{
    cursor: pointer;
    vertical-align: -webkit-baseline-middle;
    color: #3e77aa;
    font-size: 14px;
}

.contact-data .control-label{
    float: left;
    /* padding-left: 40px; */
    padding-top: 6px;
    margin-left: 0px;
}

.contact-data .form-control{
    float: right;
    margin-right: 195px;
    width: 280px;
    border: 1px solid #e4e4e4;
}

.contact-data .form-group{
    height: 40px;
    margin-bottom: 10px;
}

.content-data-body{
    font-weight: normal;

}
.content-data-body-first{
    padding-bottom: 30px;
        margin-left: 40px;
}

.content-data-body-second{
    padding-bottom: 30px;
        margin-left: 40px;
}

.content-data-second .content-data-body-second{
    display:none;
}

.content-data-body-first .next-button{
    width: 100%;
    height:40px;
}

.content-data-body-first .button{
    margin-right: 320px;
    height: 40px;
    width: 155px;
    float: right;
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

.content-data-body-department{
    height:40px;
}

.content-data-body-stock{
    background: #fff9e7;
    border: 1px solid #f1e9d3;
    width: 100%;
    height: 100px;
    -moz-border-radius: 5px;
    -webkit-border-radius:5px;
    border-radius:5px;
    margin-top: 20px;
    margin-bottom: 30px;
        padding: 10px;

}

.ordering{
    width: 50%;
    float: right;
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
        <div class="contact-data">
            <div class="content-data-first">
                <div class="contact-data-title">
                    <div class="round-button">
                        <div class="contact-data-title-img">
                            <a href="" class="round-button">1</a>
                        </div>
                    </div>
                    <div class="contacy-data-title-buttom-border">
                        <div class="contact-data-title-text">
                            Контактные данные
                        </div>
                        <div class="contact-data-title-edit">
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
                <div class="contact-data-title">
                    <div class="round-button">
                        <div class="contact-data-title-img">
                            <a href="" class="round-button">2</a>
                        </div>
                    </div>
                    <div class="contacy-data-title-buttom-border">
                        <div class="contact-data-title-text">
                            Доставка
                        </div>
                    </div>
                </div>
                <div class="content-data-body">
                    <div class="content-data-body-second">
                        <div class="content-data-body-delivery-type">
                            <div class="menu1">
                                <br id="tab2"/><br id="tab3"/>
                                <a href="#tab1">Новая почта</a><a href="#tab2">Адресная доставка</a><a href="#tab3">Самовывоз</a>
                                <div class="content-data-body-department">Отделение:</div>
                                <div class="content-data-body-address">Мои адреса:</div>
                                <div>
                                <div class="content-data-body-stock">Наш склад находится по адресу:</div>
                                </div>
                            </div>
                        </div>


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
            <div class="content-data-second">
                <div class="contact-data-title">
                    <div class="round-button">
                        <div class="contact-data-title-img">
                            <a href="" class="round-button">3</a>
                        </div>
                    </div>
                    <div class="contacy-data-title-buttom-border">
                        <div class="contact-data-title-text">
                            Доставка
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ordering">
        <?php
            echo \yii\helpers\Html::button('Оформить заказ', [
                 'type'  =>  'submit'
                    ]);
                ?>
        </div>
    </div>
</div>
