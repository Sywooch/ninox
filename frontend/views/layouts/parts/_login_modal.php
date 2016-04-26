<?php
use yii\widgets\ActiveForm;

$model = new \frontend\models\LoginForm();
$form = ActiveForm::begin([
    'action'                =>  '/login',
    'validationUrl'         =>  '/login',
    'enableAjaxValidation'  =>  true,
    'id'                    =>  'loginForm'
]);

echo $form->field($model, 'phone'),
    $form->field($model, 'password')->passwordInput(),
    $form->field($model, 'rememberMe')->checkbox(),
    \yii\bootstrap\Html::button('Войти', [
        'type'  =>  'success',
        'class' =>  'btn btn-default'
    ]);

ActiveForm::end();

echo \yii\helpers\Html::a(\Yii::t('site', 'Восстановить пароль'), \yii\helpers\Url::to('/request-password-reset')), '&nbsp; | &nbsp;',
    \yii\helpers\Html::a('Регистрация', '#registrationModal');