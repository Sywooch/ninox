<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 16.02.16
 * Time: 13:33
 */
?>
<!--<div class="write-review">
    <span>Оставить отзыв</span>
    <div class="row">
        <span>Имя и Фамилия</span>
        <input type="text" value="" name="" placeholder="Введите имя и фамилию"></input>
    </div>
    <div class="row">
        <span>Ваш Email</span>
        <input type="text" value="" name="" placeholder="Введите ваш E-mail"></input>
    </div>
    <div class="row">
        <span>Напишите ваш отзыв</span>
        <textarea name="review" placeholder="Напишите ваш отзыв"></textarea>
    </div>
    <div class="row">
        <span>Введите код с картинки</span>
        <input class="captca" title="" name="captcha" pattern="[0-9]{4}" type="text" placeholder="Введите код с
        картинки">
        <input class="reloadCaptchaz" onclick="updateCaptcha(this)" value="1" type="button">
    </div>-->
    <div class="write-review">
        <span>Оставить отзыв</span>
        <div class="row">
            <span></span>
            <label class="icon-name" for=""></label>
            <input type="text" value="" name="" placeholder="Имя и Фамилия"></input>
        </div>
        <div class="row">
            <span></span>
            <label class="icon-email" for=""></label>

            <input type="text" value="" name="" placeholder="Ваш Email"></input>
        </div>
        <div class="row">
            <span></span>
            <label class="" for=""></label>

            <textarea name="review" placeholder="Напишите ваш отзыв"></textarea>
        </div>
        <div class="row">
            <span></span>
            <label class="icon-cw" for=""></label>

            <input title="" name="captcha" pattern="[0-9]{4}" type="text" placeholder="Введите код с картинки">
        </div>
<?
echo \yii\helpers\Html::button('Отправить', [
    'type'  =>  'submit',
    'class' =>  'yellow-button large-button ',
    'id'    =>  'submit'
]);
?>
</div>
