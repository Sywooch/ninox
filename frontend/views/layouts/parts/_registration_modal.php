<?php
use kartik\form\ActiveForm;

$model = new \frontend\models\SignupForm();

$form = ActiveForm::begin([
    'action'                =>  '/register',
    'validationUrl'         =>  '/register',
    'enableAjaxValidation'  =>  true,
    'enableClientValidation'=>  false,
    'id'                    =>  'registrationForm'
]);

echo $form->field($model, 'name'),
    $form->field($model, 'surname'),
    $form->field($model, 'email'),
    $form->field($model, 'phone'),
    $form->field($model, 'password')->passwordInput(),
    $form->field($model, 'captcha')->widget(\yii\captcha\Captcha::className(), [
        'template'      =>  '{image} {input}',
        'captchaAction' =>  'site/captcharegistermodal',
    ]);
echo \yii\helpers\Html::button('Регистрация', [
    'type'  =>  'submit'
]);
ActiveForm::end();