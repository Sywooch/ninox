<?php
use kartik\form\ActiveForm;

$model = new \backend\models\CashboxCustomerForm();

$form = new ActiveForm([
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => ['labelSpan' => 4, 'deviceSize' => ActiveForm::SIZE_SMALL]
]);

$form->begin();
?>

<h3>Добавление нового клиента:</h3>

<div>
    <?=$form->field($model, 'surname')?>
    <?=$form->field($model, 'name')?>
    <?=$form->field($model, 'city')?>
    <?=$form->field($model, 'region')?>
    <?=$form->field($model, 'phone')?>
    <?=$form->field($model, 'email')?>
    <?=$form->field($model, 'cardNumber')?>
    <?=\yii\bootstrap\Html::button('Добавить', [
        'class' =>  'btn btn-default'
    ])?>
</div>
<?php $form->end()?>