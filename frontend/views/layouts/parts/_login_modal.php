
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$model = new \frontend\models\LoginForm();
$form = ActiveForm::begin([
    'action'                =>  '/login',
    'validationUrl'         =>  '/login',
    'enableAjaxValidation'  =>  true,
    'id'                    =>  'loginForm',
]);

echo
    Html::tag('div', \Yii::t('shop', 'Для добавления товаров в Избранное, пожалуйста, войдите в личный кабинет или').
        Html::a(\Yii::t('shop', ' зарегистрируйтесь'), '#registrationModal'), [
        'class'		=>	'login-modal-title',
    ]).
    $form->field($model, 'phone'),
    $form->field($model, 'password')->passwordInput(),
    Html::tag('div',
        Html::button(\Yii::t('shop', 'Войти'), [
        'type'  =>  'success',
        'class' =>  'middle-button modal-blue-button'
    ]).
        $form->field($model, 'rememberMe')->checkbox(), [
            'class'		=>	'login-modal-button',
        ]),

Html::tag('div', Html::a(\Yii::t('shop', 'Восстановить пароль'), \yii\helpers\Url::to('/request-password-reset')).
     Html::a(\Yii::t('shop', 'Регистрация'), '#registrationModal')
, [
    'class'		=>	'login-modal-reg',
]);

ActiveForm::end();

/*echo Html::a(\Yii::t('shop', 'Восстановить пароль'), \yii\helpers\Url::to('/request-password-reset')), '&nbsp; | &nbsp;',
     Html::a(\Yii::t('shop', 'Регистрация'), '#registrationModal');*/
