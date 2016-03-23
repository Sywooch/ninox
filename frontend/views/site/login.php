<?php
use kartik\form\ActiveForm;
?>
<span class="close"></span>
<?php

$form = new ActiveForm([
    'type'          => ActiveForm::TYPE_HORIZONTAL,
    'formConfig'    => [
        'labelSpan' => 3,
        'deviceSize' => ActiveForm::SIZE_SMALL
    ]
]);

echo $form->field($model, 'phone'),
$form->field($model, 'password')->passwordInput();

echo \yii\bootstrap\Html::button(\Yii::t('shop', 'Войти'), [
    'class' =>  'yellowButton largeButton'
])

?>
<div class="row center">
    <span class="recovery link-hide blue" data-href="/cabinet/recovery">Восстановить пароль</span><span> | </span><span class="registration link-hide blue" data-href="/cabinet/registration">Регистрация</span>
</div>