<?php
use kartik\form\ActiveForm;

$model = new \backend\models\CashboxCustomerForm();

$form = new ActiveForm([
    'formConfig'    =>  ['labelSpan' => 4, 'deviceSize' => ActiveForm::SIZE_SMALL],
    'id'            =>  'newCustomerForm',
    'validateOnType'=>  true
]);

$form->begin();
?>

<h3 style="margin-top: 0">Добавление нового клиента:</h3>

<div>
<?=$form->field($model, 'surname')?>
<?=$form->field($model, 'name')?>
<?=$form->field($model, 'city')?>
<?=$form->field($model, 'region')?>
<?=$form->field($model, 'phone')?>
<?=$form->field($model, 'email')?>
<?=$form->field($model, 'cardNumber')?>
<?=\yii\bootstrap\Html::button('Добавить', [
    'class' =>  'btn btn-default',
    'type'  =>  'submit'
])?>
</div>
<?php $form->end()?>