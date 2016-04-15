<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 13.04.16
 * Time: 14:43
 */

$css = <<<'CSS'

.callback {
text-align: left;
}

.callback textarea{
    width: 100%;
    max-width: 100%;
}

.callback .head{
    font-size: 30px;
padding-bottom: 30px;
display: block;
color: #4f4f4f;
}

.callback .row{
    margin: 0px;
    margin-bottom: 25px;
}

.callback .title{
    width: 200px;
    display: inline-block;
    line-height: 40px;
    font-size: 16px;
    color: rgb(79, 79, 79);
}

.callback .fio input, .callback .phone input{
    height: 40px;
    float: right;
    width: 300px;
}

.callback input.captcha{
    margin: auto;
    display: block;
    float: left;
    height: 40px;
}

.callback .captcha .title{
    float: left;
}

CSS;

$this->registerCss($css);
?>
<div class="callback">
    <span class="head">Запрос на перезвон</span>
    <div class="row">
        <div class="fio">
            <span class="title">Имя и фамилия</span><input name="fio" type="text">
        </div>
    </div>
    <div class="row">
        <div class="phone">
            <span class="title">Ваш телефон</span><span class="flag"></span><input placeholder="+_(___)___-____" class="input_phone" name="phone" value="" type="text">
        </div>
    </div>
    <div class="row">
        <span class="title">Сообщение</span>
        <textarea name="callback"></textarea>
    </div>
    <div class="row">
        <div class="captcha">
            <span class="title">Введите код с картинки</span><input class="captcha" title="" name="captcha"
                                                                    pattern="[0-9]{4}"
                                                                    type="text"><img src="https://krasota-style.com.ua/captcha.png?0.3437326502675966" alt="captcha image" pagespeed_url_hash="383813741" onload="pagespeed.CriticalImages.checkImageForCriticality(this);" height="40" width="80">
            <input class="reloadCaptchaz" onclick="updateCaptcha(this)" value="1" type="button">
        </div>
    </div>
</div>
