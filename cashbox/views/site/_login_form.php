<?php use kartik\form\ActiveForm;
use yii\bootstrap\Html;

$form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => [

    ],
    'fieldConfig' => [
        'template' => "{input} {error}",
        'labelOptions' => ['class' => 'col-lg-1 control-label'],
    ],
]); ?>

<?=$form->errorSummary($model, [
    'header'    =>  'При авторизации возникли некоторые ошибки: '
])?>
    <div class="input-group input-group-lg">
        <span class="input-group-addon"><i class="fa fa-user"></i></span>
        <?=Html::activeTextInput($model, 'username', [
            'class'         =>  'form-control',
            'placeholder'   =>  'Логин'
        ])?>
    </div>

    <div class="input-group input-group-lg">
        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
        <?=Html::activeTextInput($model, 'password', [
            'class'         =>  'form-control',
            'placeholder'   =>  'Пароль',
            'type'          =>  'password'
        ])?>
    </div>
<?= Html::submitButton('Войти', ['class' => 'float', 'name' => 'login-button']) ?>
<?php $form->end(); ?>