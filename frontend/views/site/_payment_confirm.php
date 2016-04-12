<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 12.04.16
 * Time: 11:59
 */

$css = <<<'CSS'

.form_content .cap{
    color: #acacac;
    font-size: 12px;
    display: inline-block;
    padding-bottom: 20px;
}

.form_content .row .title{
    margin: 0px;
    display: block;
    float: left;
    font-size: 16px;
    color: #4f4f4f;
    line-height: 40px;
    margin-right: 15px;
}

.form_content .row{
    margin: 0px;
    padding-bottom: 25px;
}

.form_content{
    text-align: left;
}

.order_number{
    float: left;
}

.form_content input{
    height: 40px;
}

.order_number input{
    width: 100px;
}

.summ{
    float: left;
    margin-left: 35px;
}

.summ input{
    width: 110px;
}

.payDate input{
    float: left;
}

CSS;

$this->registerCss($css);

?>

<div class="form_content" id="payMessage">
    <div class="cap">
        1. Введите данные заказа
    </div>
    <div class="row">
        <div class="order_number">
            <span class="title">Номер заказа</span>
            <input name="order_number" type="text">
        </div>
        <div class="summ">
            <span class="title">Сумма оплаты</span>
            <input name="summ" type="text">
        </div>
    </div>
    <div class="cap">
        2. Как вы платили
    </div>
    <div class="row">
        <div class="payDate">
            <span class="title">Дата оплаты</span><input aria-owns="P1507405932_root P1507405932_hidden" aria-readonly="false" aria-expanded="false" aria-haspopup="true" class="picker__input" id="P1507405932" readonly="" name="" type="text">
        </div>
    <div class="line"></div>
    </div>
</div>
