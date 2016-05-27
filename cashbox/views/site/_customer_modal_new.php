<?php
use kartik\form\ActiveForm;
use yii\bootstrap\Html;

$model = new \cashbox\models\CustomerForm();

$form = ActiveForm::begin([
    'formConfig'    =>  ['labelSpan' => 4, 'deviceSize' => ActiveForm::SIZE_SMALL],
    'id'            =>  'newCustomerForm',
    'type' => ActiveForm::TYPE_VERTICAL,
    'validateOnType'=>true,
]);

echo Html::tag('h3', 'Добавление нового клиента:', ['style' => 'margin-top: 0']);

echo Html::tag('div',
    $form->field($model, 'surname').
    $form->field($model, 'name').
    $form->field($model, 'city').
    $form->field($model, 'region').
    $form->field($model, 'phone').
    $form->field($model, 'email').
    $form->field($model, 'cardNumber').
    Html::button('Добавить', [
        'class' =>  'btn btn-default',
        'type'  =>  'submit'
    ]), []);

$form->end();