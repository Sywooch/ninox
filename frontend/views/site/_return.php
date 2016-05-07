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

$form = \yii\bootstrap\ActiveForm::begin([
    'id'    =>  'return-form'
]);

echo Html::tag('div',
    Html::tag('span', '1. '.\Yii::t('shop', 'Введите данные заказа'), ['class' => 'cap']).
    $form->field($model, 'orderNumber').
    $form->field($model, 'customerPhone').
    Html::tag('span', '2. '.\Yii::t('shop', 'Введите данные ТТН "Новая Почта"'), ['class' => 'cap']).
    $form->field($model, 'sendDate')
        ->widget(DatePicker::className(), [
            'type' => DatePicker::TYPE_INPUT,
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'mm-dd-yyyy'
            ]
        ]).
    $form->field($model, 'nakladna').
    Html::tag('span', '3. '.\Yii::t('shop', 'Ваш комментарий (не обязательно)'), ['class' => 'cap']).
    $form->field($model, 'comment')
        ->textarea().
    Html::tag('div',
        Html::tag('div',
            Html::tag('span',
                '4. '.\Yii::t('shop', 'Причина возврата').
                $form->field($model, 'brokenGood')
                    ->checkbox().
                $form->field($model, 'notMatchGood')
                    ->checkbox().
                $form->field($model, 'notLikeGood')
                    ->checkbox(),
                [
                    'class' => 'cap'
                ]
            ),
            [
                'class' => 'left'
            ]
        ).
        '5. '.\Yii::t('shop', 'Способ возврата денег').
        $form->field($model, 'refundMethod')->radioList($model->refundMethods),
        [
            'class' =>  'check-buttons'
        ]
    ).
    Html::tag('span', '6. '.\Yii::t('shop', 'Введите номер банковской карты (*перевод возможен только на карту ПриватБанк)'), ['class' => 'cap']).
    $form->field($model, 'cardNumber').
    $form->field($model, 'cardHolder').
    Html::submitButton(\Yii::t('shop', 'Отправить'),
        [
            'class' => 'about-inform-button yellow-button large-button', 'name' => 'return-button'
        ]
    ),
    [
        'class' =>  'vozvrat-modal'
    ]
);

$form->end();