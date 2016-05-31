<?php
use kartik\form\ActiveForm;

$form = ActiveForm::begin([
    'type'  =>  ActiveForm::TYPE_HORIZONTAL,
    'id'    =>  'extendedSearch',
    'formConfig' => ['labelSpan' => 5, 'deviceSize' => ActiveForm::SIZE_SMALL]
]);

echo $form->field($model, 'moneyConfirmed', ['options' => ['class' => 'col-xs-4']])->dropDownList(['' => 'все', '0' => 'не подтверждена', '1' => 'подтверждена']),
    $form->field($model, 'nakladnaStatus', ['options' => ['class' => 'col-xs-4']])->dropDownList(['' => 'все', '0' => 'не введена', '1' => 'введена']),
    $form->field($model, 'status', ['options' => ['class' => 'col-xs-4']])->dropDownList(array_merge(['' => 'все'], $model->statuses)),
    $form->field($model, 'actualAmountStatus', ['options' => ['class' => 'col-xs-4']])->dropDownList(['' => 'все', '0' => 'не введена', '1' => 'введена']),
    $form->field($model, 'paymentType', ['options' => ['class' => 'col-xs-4']])->dropDownList(array_merge(['0' => 'все'], \Yii::$app->runAction('orders/default/get-payments', ['type' => 'paymentType']))),
    $form->field($model, 'showDeleted', ['options' => ['class' => 'col-xs-4']])->dropDownList(['0'  =>  'Скрыть', '1' => 'Показать']),
    \yii\bootstrap\Html::tag('div',
        \yii\bootstrap\Html::button('Искать!', ['class' => 'btn btn-success btn-lg center-block', 'type' => 'submit']),
        [
            'class' =>  'col-xs-12'
        ]);

$form->end();