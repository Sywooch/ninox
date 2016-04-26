<?php
use kartik\form\ActiveForm;

$model = new \frontend\models\SignupForm();

$form = ActiveForm::begin([
    'action'                =>  '/register',
    'validationUrl'         =>  '/register',
    'enableAjaxValidation'  =>  true,
    'id'                    =>  'registrationForm'
]);

echo $form->field($model, 'name'),
    $form->field($model, 'surname'),
    $form->field($model, 'email'),
    $form->field($model, 'phone')->widget(\frontend\widgets\MaskedInput::className(), [
        'clientOptions' =>  [
            'clearIncomplete'   =>  true,
            'alias'             =>  'phone',
            'url'               =>  \yii\helpers\Url::to('/js/phone-codes.json'),
            'onBeforePaste'     =>  new \yii\web\JsExpression('function(){ return false; }')
        ],
    ]),
    $form->field($model, 'countryCode', ['options' => ['style' => 'display: none'], 'inputOptions' => ['id' => 'countryCode']])->hiddenInput()->label(false),
    $form->field($model, 'password')->passwordInput(),
    $form->field($model, 'captcha')->widget(\yii\captcha\Captcha::className(), [
        'template'      =>  '{image} {input}',
        'captchaAction' =>  'site/captcharegistermodal',
    ]);
echo \yii\helpers\Html::button('Регистрация', [
    'type'  =>  'submit'
]);
ActiveForm::end();