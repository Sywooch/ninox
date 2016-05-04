<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 08.12.15
 * Time: 17:31
 */

\rmrevin\yii\fontawesome\AssetBundle::register($this);

$js = <<<'JS'
$("body").on('submit', '#changePasswordForm', function(e){
    e.preventDefault();
    
    var form = $(this),
        formData = form.serialize();
    
    form.html('<i class="fa fa-refresh fa-spin"></i>');
    
    $.ajax({
        type: 'POST',
        url: '/account/password-change',
        data: formData,
        success: function(data){
            form.html('Пароль успешно изменён!');
        }
    });
});
JS;

$this->registerJs($js);

$model = new \frontend\modules\account\models\ChangePasswordForm();

$form = \kartik\form\ActiveForm::begin([
    'action'                =>  '/account/password-change',
    'validationUrl'         =>  '/account/password-change',
    'enableAjaxValidation'  =>  true,
    'id'                    =>  'changePasswordForm'
]);

echo $form->field($model, 'oldPassword')->passwordInput(),
    $form->field($model, 'newPassword')->passwordInput(),
    $form->field($model, 'newPassword_repeat')->passwordInput(),
    \yii\bootstrap\Html::button(\Yii::t('shop', 'Сохранить'), ['type' => 'submit']);

$form->end();