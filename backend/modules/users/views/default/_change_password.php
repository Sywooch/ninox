<button class="remodal-close" data-remodal-action="close"></button>
<?php
$form = new \yii\widgets\ActiveForm();
$pjax = new \yii\widgets\Pjax();

$pjax->begin();
$form->begin(['options' => ['data-pjax' => true], 'id' => 'changePasswordForm']);

if(\Yii::$app->user->identity->superAdmin != 1){
    /*echo $form->field($model, 'password', [
        'options'   =>  [
            'value' =>  ''
        ]
    ])->passwordInput();*/
}

echo $form->field($model, 'newPassword')->passwordInput(),
    $form->field($model, 'newPassword2')->passwordInput(),
    $form->field($model, 'id')->hiddenInput([
        'style' =>  'display: none'
    ])->label(false);
?>
<button class="remodal-cancel" label="Cancel" data-remodal-action="cancel">Cancel</button>
<button class="remodal-confirm" label="OK" type="submit">OK</button>
<?php
$form->end();
$pjax->end();
?>