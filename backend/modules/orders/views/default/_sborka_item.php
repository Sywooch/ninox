<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 10.04.16
 * Time: 15:47
 */

$typeaheadStyles = <<<'CSS'

.sborka{
    background: #eae9e9;
    width: 700px;
    margin-bottom: 50px;
    padding-bottom: 10px;
}

.sborka .header{
    padding: 25px 35px;
    overflow: auto;
}

.sborka .header span{
    font-size: 36px;
    font-weight: bold;
    color: #464646;
}

.sborka .button{
    border: none;
    color: white;
    font-size: 18px;
    font-weight: bold;
}

.sborka .green-button{
    background: #95cc3e;
    border-bottom: 3px solid #628d1d;
}

.sborka .yellow-button{
    background: #ffc600;
    border-bottom: 3px solid #daa901;
    color: #503f05;
}

.sborka .red-button{
    background: #dd3939;
    border-bottom: 3px solid #9b3434;
}

.sborka .grey-button{
    background: #cbcbcb;
    border-bottom: 3px solid #a4a4a4;
}

.sborka .small-button{
    width: 148px;
    height: 48px;
}

.sborka .medium-button{
    height: 114px;
    width: 50%;
    float: left;
}

.sborka .header .yellow-button{
    float: left;
}

.sborka .header .green-button{
    float: right;
}

.sborka .order-number{
    width: 334px;
    float: left;
    text-align: center;
}

.sborka .typical-block{
    height: 260px;
    width: 685px;
    background: white;
    margin: auto;
    display: block;
    box-shadow: 0px 4px 5px #888888;
}

.sborka .typical-block .image{
    width: 50%;
    height: 100%;
    float: left;
}

.sborka .typical-block .content{
    float: left;
    width: 50%;
}

.sborka .items-count{
    width: 100%;
    height: 146px;
    padding: 50px;
}

.sborka .items-count input{
    width: 64px;
    height: 43px;
    font-size: 36px;
    font-weight: bold;
    text-align: center;
    border: 2px solid #cecece;
    color: #464646;
    vertical-align: middle;
}

.sborka .items-count a{
    font-size: 18px;
    font-weight: bold;
    text-decoration: underline;
    color: #464646;
    margin-left: 15px;
}

.sborka .items-count span{
    display: block;
    font-size: 18px;
    font-weight: bold;
    color: #464646;

}

.sborka .items-count .count{
    float: right;
    height: 42px;
    overflow: hidden;
    line-height: initial;
}

.sborka .image img{
    height: 100%;
    width: 100%;
}

.sborka .typical-block .access .ico{
    background: url("/img/access.png") no-repeat;
    position: relative;
    width: 113px;
    height: 113px;
    margin: auto;
    display: block;
    margin-top: -180px;
}

.sborka .typical-block .denied .ico{
    background: url("/img/denied.png") no-repeat;
    position: relative;
    width: 113px;
    height: 113px;
    margin: auto;
    display: block;
    margin-top: -180px;
}


CSS;

$this->registerCss($typeaheadStyles);
?>

    <div class="sborka">
        <div class="header">
            <?=\yii\helpers\Html::button('К заказам', [
                'type'  =>  'submit',
                'class' =>  'yellow-button small-button button',
                'id'    =>  'submit'
            ])?>
            <div class="order-number">
                <span>32951</span>
            </div>
            <?=\yii\helpers\Html::button('Сохранить', [
                'type'  =>  'submit',
                'class' =>  'green-button small-button button',
                'id'    =>  'submit'
            ])?>
        </div>
        <div class="typical-block">
            <div class="image denied">
                <img src="">
                <div class="ico"></div>
            </div>
            <div class="content">
                <div class="items-count">
                    <input type="text">
                    <a href="">OK</a>
                    <div class="count">
                        <span>2035456</span>
                        <span>1 ШТ.</span>
                    </div>
                </div>
                <div class="buttons">
                    <?=\yii\helpers\Html::button('В ЗАКАЗ', [
                        'type'  =>  'submit',
                        'class' =>  'green-button /*grey-button*/ medium-button button',
                        'id'    =>  'submit'
                    ])?>
                    <?=\yii\helpers\Html::button('НЕ МОГУ НАЙТИ', [
                        'type'  =>  'submit',
                        'class' =>  'red-button /*grey-button*/ medium-button button',
                        'id'    =>  'submit'
                    ])?>
                </div>
            </div>
        </div>
    </div>

