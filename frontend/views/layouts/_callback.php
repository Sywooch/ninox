<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 13.04.16
 * Time: 14:43
 */

use yii\helpers\Html;

$css = <<<'CSS'

.callback {
text-align: left;
}

.callback .form-group textarea{
    display: block;
    width: 100% !important;
    max-width: 100%;
    min-height: 60px;
}

.callback .head{
    font-size: 30px;
padding-bottom: 30px;
display: block;
color: #4f4f4f;
}

.callback .form-group{
    overflow: auto;
}

.callback .form-group input{
    width: 50%;
    float: right;
}

.callback .form-group label{
    line-height: 34px;
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

.callback .captcha{
padding-bottom: 25px;
}

.callback .captcha .title{
    float: left;
}

.callback .field-callbackform-captcha img{
    height: 35px;
    margin-left: 30px;
}

CSS;

$this->registerCss($css);

$model = new \frontend\models\CallbackForm();

?>
<div class="callback">
    <?php    $form = \yii\bootstrap\ActiveForm::begin([
        'id'            =>  'callback-form'
    ]);
    ?>
    <span class="head">Запрос на перезвон</span>
        <?= $form->field($model, 'name')?>
        <?= $form->field($model, 'phone')?>
        <?= $form->field($model, 'question')->textarea()?>
        <?=$form->field($model, 'captcha')->widget(\yii\captcha\Captcha::className());?>
        <?= Html::submitButton('Отправить', ['class' => 'about-inform-button yellow-button large-button', 'name' =>
        'callback-button']) ?>
</div>
<?php $form->end(); ?>

