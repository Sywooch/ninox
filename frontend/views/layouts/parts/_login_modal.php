<?php
use yii\helpers\Html;
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
    Html::button(\Yii::t('shop', 'Войти'), [
        'type'  =>  'success',
        'class' =>  'btn btn-default'
    ]);

ActiveForm::end();

echo Html::a(\Yii::t('shop', 'Восстановить пароль'), \yii\helpers\Url::to('/request-password-reset')), '&nbsp; | &nbsp;',
    Html::a(\Yii::t('shop', 'Регистрация'), '#registrationModal');
