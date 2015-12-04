<?php
$model = new \frontend\models\LoginForm();
$form = new \yii\widgets\ActiveForm([
    'action'    =>  'login'
]);

$form->begin();
echo $form->field($model, 'phone'),
    $form->field($model, 'password')->passwordInput(),
    $form->field($model, 'rememberMe')->checkbox(),
    \yii\bootstrap\Html::button('Войти', [
        'type'  =>  'success',
        'class' =>  'btn btn-default'
    ]);
$form->end();