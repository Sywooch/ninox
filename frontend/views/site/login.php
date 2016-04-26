<?php
use kartik\form\ActiveForm;
use yii\bootstrap\Html;

echo Html::tag('span', '', ['class' => 'close']);

$form = ActiveForm::begin([
    'action'                =>  '/login',
    'validationUrl'         =>  '/login',
    'validateOnType'        =>  true,
    'enableAjaxValidation'  =>  true,
    'type'          => ActiveForm::TYPE_HORIZONTAL,
    'formConfig'    => [
        'labelSpan' => 3,
        'deviceSize' => ActiveForm::SIZE_SMALL
    ]
]);

echo $form->field($model, 'phone'),
    $form->field($model, 'password')->passwordInput(),
    Html::button(\Yii::t('shop', 'Войти'), [
        'class' =>  'yellowButton largeButton'
    ]);

ActiveForm::end();

?>
<div class="row center">
    <span class="recovery link-hide blue" data-href="/cabinet/recovery">Восстановить пароль</span><span> | </span><span class="registration link-hide blue" data-href="/cabinet/registration">Регистрация</span>
</div>