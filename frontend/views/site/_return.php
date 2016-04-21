<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 11.04.16
 * Time: 15:35
 */

use kartik\date\DatePicker;
use yii\helpers\Html;

$css = <<<'CSS'

.vozvrat-modal .form-group {
    float: left;
    width: 50%;
}

.vozvrat-modal .check-buttons .form-group{
    width: auto;
    float: none;
}

.field-returnform-senddate input{
    width: 50%;
}

.field-returnform-ordernumber input{
    width: 50%;
}

.vozvrat-modal .field-returnform-comment{
width: 100%;
}

.vozvrat-modal{
    text-align: left;
}

.field-returnform-comment textarea{
    display: block;
    width: 100% !important;
    max-width: 100%;
    min-height: 60px;
}

.remodal button{
    float: none;
    margin: auto;
    display: block;
}

.vozvrat-modal .cap{
    color: #acacac;
    font-size: 12px;
    display: block;
    padding-bottom: 20px;
}

.vozvrat-modal .check-buttons{
    overflow: auto;
    width: 100%;
}

.vozvrat-modal .row .title{
    margin-left: 20px;
    line-height: 40px;
    font-size: 16px;
    margin-right: 20px;
}

.vozvrat-modal .row{
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
    float: left;
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

.card_owner_name input{
    width: 300px;
}

.vozvrat-modal .field-returnform-cardnumber, .vozvrat-modal .field-returnform-cardholder{
    width: 100%;
}

.vozvrat-modal .field-returnform-cardnumber label, .vozvrat-modal .field-returnform-cardholder label{
    float: left;
    line-height: 40px;
}

.vozvrat-modal .field-returnform-cardnumber input, .vozvrat-modal .field-returnform-cardholder input{
    width: 50%;
    float: right;
}

.vozvrat-modal .check-buttons .field-returnform-refundmethod{
    width: 50%;
    float: left;
}

.field-returnform-refundmethod .control-label{
    display: none;
}


CSS;

$this->registerCss($css);

$model = new \frontend\models\ReturnForm();

?>
<div class="vozvrat-modal">
    <?php    $form = \yii\bootstrap\ActiveForm::begin([
    'id'            =>  'return-form'
    ]);
 ?>
    <span class="cap">1. Введите данные заказа</span>
                <?= $form->field($model, 'orderNumber')?>
                <?= $form->field($model, 'customerPhone')?>
    <span class="cap">2. Введите данные ТТН "Новая Почта"</span>
                <?php
                echo $form->field($model, 'sendDate')->widget(DatePicker::classname(), [
                    'type' => DatePicker::TYPE_INPUT,
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'mm-dd-yyyy'
                    ]
                ]);?>
                <?= $form->field($model, 'nakladna')?>
    <span class="cap">3. Ваш комментарий (не обязательно) </span>
    <?= $form->field($model, 'comment')->textarea()?>
    <div class="check-buttons">
        <div class="left">
            <span class="cap">
                4. Причина возврата
            </span>
            <?= $form->field($model, 'brokenGood')->checkbox()?>
            <?= $form->field($model, 'notMatchGood')->checkbox()?>
            <?= $form->field($model, 'notLikeGood')->checkbox()?>
        </div>
            <span class="cap">
                5. Способ возврата денег
            </span>
        <?= $form->field($model, 'refundMethod')->radioList($model->refundMethods);?>
    </div>
    <span class="cap">6. Введите номер банковской карты (*перевод возможен только на карту ПриватБанк)</span>
        <?= $form->field($model, 'cardNumber')?>
        <?= $form->field($model, 'cardHolder')?>
        <?= Html::submitButton('Отправить', ['class' => 'about-inform-button yellow-button large-button', 'name' =>
            'return-button']) ?>
</div>
<?php $form->end(); ?>

<!--
   тут недавно один парниша, патлач как прозвали, познакомися с тянкой, превосходной и милой, она увлекалась вязанием
    спортом, и как же умна была и красива, они говорили по телефону, часами




    так вот в один день да такой судьбоносный, когда ничего не вещало беды,



    взяло да
    случилось и знай бы он что, все было бы так превосходно, опустился чс на вк
    опустился
которая увекается всем чем и он и даже боьше







-->