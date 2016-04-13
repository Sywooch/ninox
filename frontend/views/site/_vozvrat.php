<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 11.04.16
 * Time: 15:35
 */

$css = <<<'CSS'

.vozvrat-modal .left {
    float: left;
}

.vozvrat-modal{
    text-align: left;
}

.vozvrat-modal textarea{
    display: block;
    width: 100%;
    max-width: 100%;
}

.remodal button{
    float: none;
}

.vozvrat-modal .cap{
    color: #acacac;
    font-size: 12px;
    display: inline-block;
    padding-bottom: 20px;
    padding-top: 20px;
}

.vozvrat-modal .row .title{
    margin-left: 20px;
    line-height: 40px;
    font-size: 16px;
    margin-right: 20px;
}

.vozvrat-modal .row{
    /*
    margin: 0px;
    */
}

.vozvrat-modal  input{
height: 40px;
}


CSS;

$this->registerCss($css);

?>
<div class="vozvrat-modal">
    <span class="cap">1. Введите данные заказа </span>
    <div class="row">
        <div class="order_number left">
            <span class="title">
                № заказа
            </span>
            <input type="text">
        </div>
        <div class="phone">
            <span class="title">
                Тел. отправителя
            </span>
            <input placeholder="+_(___)___-____" class="input_phone" name="phone" value="" type="text">
        </div>
    </div>
    <span class="cap">2. Введите данные ТТН "Новая Почта"</span>
    <div class="row">
        <div class="left">
            <span class="title">
                Дата отправки
            </span>
            <input type="text">
        </div>
        <div>
            <span class="title">
                № ТТН
            </span>
            <input type="text">
        </div>
    </div>
    <span class="cap">3. Ваш комментарий (не обязательно) </span>
    <textarea name="comment"></textarea>
    <div>
        <div class="left">
            <span class="cap">
                4. Причина возврата
            </span>
            <div></div>
        </div>
        <div>
            <span class="cap">
                5. Способ возврата денег
            </span>
            <div></div>
        </div>
    </div>
    <span class="cap">6. Введите номер банковской карты (*перевод возможен только на карту ПриватБанк) </span>
    <div class="row">
        <span class="left title">
            Номер карты для возврата денег*
        </span>
        <input type="text">
    </div>
    <div class="row">
        <span class="left title">
            Имя и фамилия владельца карты
        </span>
        <input type="text">
    </div>
</div>
<!--
   тут недавно один парниша, патлач как прозвали, познакомися с тянкой, превосходной и милой, она увлекалась вязанием
    спортом, и как же умна была и красива, они говорили по телефону, часами




    так вот в один день да такой судьбоносный, когда ничего не вещало беды,



    взяло да
    случилось и знай бы он что, все было бы так превосходно, опустился чс на вк
    опустился
которая увекается всем чем и он и даже боьше







-->