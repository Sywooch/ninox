<?php
$model = new \frontend\models\SignupForm();

$form = new \yii\widgets\ActiveForm([
    'action'    =>  'register'
]);

$form->begin();

echo $form->field($model, 'name'),
    $form->field($model, 'surname'),
    $form->field($model, 'email'),
    $form->field($model, 'phone'),
    $form->field($model, 'password'),
    $form->field($model, 'captcha')->widget(\yii\captcha\Captcha::className(), [
        'template'      =>  '{image} {input}',
        'captchaAction' =>  '/captcharegistermodal',
        'id'            =>  'captcha-modal'
    ]);
echo \yii\helpers\Html::button('Регистрация', [
    'type'  =>  'submit'
]);

$form->end();