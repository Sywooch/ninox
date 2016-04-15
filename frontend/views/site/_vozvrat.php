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
    padding-bottom: 25px;
}

.vozvrat-modal  input{
    height: 40px;
}


.check-buttons .row{
    margin: 0px;
}

.check-buttons .left{
    width: 50%;
}

.check-buttons input{
    height: auto;
    float: left;
}

.check-buttons label{
    display: block;
    margin-bottom: 20px;
    padding-left: 40px;
    line-height: 23px;
    font-size: 16px;
    color: #4f4f4f;
    font-weight: unset;
}

.card .title{
    width: 300px;
}

.card_owner_name input{
    width: 300px;
}

.vozvrat-modal .check-buttons{
    overflow: auto;
    padding-top: 25px;
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
    <div class="check-buttons">
        <div class="left">
            <span class="cap">
                4. Причина возврата
            </span>
            <div class="row">
                <input name="defect" id="defect" type="checkbox">
                <label class="lite-green-check" name="defect_lbl" for="defect">брак товара</label>
                <input name="variance" id="variance" type="checkbox">
                <label class="lite-green-check" name="variance_lbl" for="variance">не соответствует заказу</label>
                <input name="incompatibility" id="incompatibility" type="checkbox">
                <label class="lite-green-check" name="incompatibility_lbl" for="incompatibility">просто не подошел</label>
            </div>
        </div>
        <div class="left">
            <span class="cap">
                5. Способ возврата денег
            </span>
            <div class="row">
                <input name="returnmoney" id="returnmoney_oncard" checked="checked" type="radio">
                <label class="lite-green-radio" name="returnmoney_oncard_lbl" for="returnmoney_oncard">на карту ПриватБанк</label>
                <input name="returnmoney" id="returnmoney_onaccount" type="radio">
                <label class="lite-green-radio" name="returnmoney_onaccount_lbl" for="returnmoney_onaccount">добавить на ваш личный счет Krasota-Style.ua</label>
            </div>
        </div>
    </div>
    <span class="cap">6. Введите номер банковской карты (*перевод возможен только на карту ПриватБанк) </span>
    <div class="row card card_number">
        <span class="left title">
            Номер карты для возврата денег*
        </span>
        <input type="text">
    </div>
    <div class="row card card_owner_name">
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