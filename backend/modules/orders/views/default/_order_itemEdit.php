<?php
use yii\bootstrap\Html;

echo Html::button('', ['class' => 'remodal-close', 'data-remodal-action' => 'close']);

$form = \kartik\form\ActiveForm::begin([
    'fieldConfig' => [
        'template'  =>  Html::tag('div', Html::tag('div', '{label}', ['class' => 'col-xs-4']).Html::tag('div', '{input}', ['class' => 'col-xs-5']).Html::tag('div', '{error}', ['class' => 'col-xs-3']), ['class' => 'row']),
        'labelOptions' => ['class' => 'control-label'],
    ],
    'options'   =>  [
        'enctype'   => 'multipart/form-data',
        'id'        =>  'editGoodForm'
    ]
]);

echo Html::tag('h3', 'Редактирование товара'),
$form->field($model, 'itemID')->hiddenInput()->label(false),
$form->field($model, 'orderID')->hiddenInput()->label(false),
$form->field($model, 'name'),
$form->field($model, 'count'),
$form->field($model, 'originalPrice')->label('Цена за штуку'),
$form->field($model, 'discountType')->dropDownList($model::$DISCOUNT_TYPES),
$form->field($model, 'discountSize'),
Html::button('Сохранить', ['type' => 'submit', 'class' => 'btn btn-large btn-success']);

$form->end();