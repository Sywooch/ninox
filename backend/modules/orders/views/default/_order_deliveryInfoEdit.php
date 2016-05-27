<?php

use kartik\form\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

$form = ActiveForm::begin([
    'id' => 'login-form-horizontal',
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => ['labelSpan' => 4, 'deviceSize' => ActiveForm::SIZE_SMALL]
]);

echo Html::tag('h4', 'Редактирование инфо о доставке в заказе', ['style' => 'margin-top: -15px; padding-bottom: 10px']),
    $form->field($model, 'city'),
    $form->field($model, 'region')->dropDownList((new \common\models\History())->getRegions()),
    Html::tag('hr'),
    $form->field($model, 'deliveryType', [
        'inputOptions'  =>  [
            'id'    =>  'deliveryTypeInput'
        ]
    ])->dropDownList(\Yii::$app->runAction('orders/default/get-deliveries', ['type' => 'deliveryType'])),
    Html::hiddenInput('deliveryParamInput', $model->deliveryParam, ['id' => 'deliveryParamInput']),
    $form->field($model, 'deliveryParam')
        ->widget(\kartik\depdrop\DepDrop::className(), [
            'pluginOptions' =>  [
                'depends'   =>  ['deliveryTypeInput'],
                'initialize'=>  true,
                'params'    =>  [
                    'deliveryTypeInput',
                    'deliveryParamInput'
                ],
                'emptyMsg'  =>  'варианты отсутствуют',
                'initDepends'=>  ['deliveryTypeInput'],
                'url'       =>  Url::to('/orders/get-deliveries')
            ]
        ]),
    $form->field($model, 'deliveryInfo');

echo Html::button('Сохранить', ['class' => 'btn btn-success btn-lg', 'type' => 'success']);

$form->end();