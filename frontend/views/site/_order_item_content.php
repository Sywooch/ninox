<?php
        use yii\bootstrap\ActiveForm;
?>
<div class="content-data-body-first">
    <?=$form->field($model, 'customerName'),
    $form->field($model, 'customerSurname'),
    $form->field($model, 'deliveryCity'),
    $form->field($model, 'deliveryRegion'),
    $form->field($model, 'customerEmail')?>
    <div class="next-button">
        <?php
        /*$form = ActiveForm::begin([
            'id' => '',
            'enableClientValidation'=> true,
            'validateOnSubmit' => true, // this is redundant because it's true by default
        ]);

        /*$form = $this->beginWidget('CActiveForm', [
            'clientOptions' => [
                'afterValidate' => 'js:function(form, data, hasError) {if(!hasError){$('.[type=>submit].').removeAttr('.disabled.') } }'  ]]);*/

        echo \yii\helpers\Html::button('Далее', [
            'class'     =>  'button yellow-button middle-button goToPage',
            'type'      =>  'button',
	        'data-page' =>  1,
        ]);
        ?>
    </div>
</div>