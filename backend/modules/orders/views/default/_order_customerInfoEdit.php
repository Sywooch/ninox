<?php

use kartik\form\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

$form = ActiveForm::begin([
    'id' => 'login-form-horizontal',
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => ['labelSpan' => 4, 'deviceSize' => ActiveForm::SIZE_SMALL]
]);

echo Html::tag('h4', 'Редактирование инфо о клиенте в заказе', ['style' => 'margin-top: -15px; padding-bottom: 10px']),
Html::tag('hr'),
$form->field($model, 'name'),
$form->field($model, 'surname'),
$form->field($model, 'phone'),
$form->field($model, 'email');
echo Html::tag('hr'),
Html::hiddenInput('orderID', $model->order->ID, ['id' => 'orderID']),
$form->field($model, 'coupon'),
$form->field($model, 'paymentType',
    [
        'inputOptions'  =>  [
            'id'    =>  'paymentTypeInput'
        ]
    ])->dropDownList(\Yii::$app->runAction('orders/default/get-payments', ['type' => 'paymentType'])),
$form->field($model, 'paymentParam')
    ->widget(\kartik\depdrop\DepDrop::className(), [
        'pluginOptions' =>  [
            'depends'   =>  ['paymentTypeInput'],
            'initialize'=>  true,
            'params'    =>  [
                'orderID'
            ],
            'emptyMsg'  =>  'варианты отсутствуют',
            'initDepends'=>  ['paymentTypeInput'],
            'url'       =>  Url::to('/orders/get-payments')
        ]
    ]);

echo Html::button('Сохранить', ['class' => 'btn btn-success btn-lg', 'type' => 'success']);

$form->end();