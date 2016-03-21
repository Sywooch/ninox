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
        <?=\yii\helpers\Html::button('Далее', [
            'class'     =>  'button yellow-button middle-button goToPage',
            'type'      =>  'button',
	        'data-page' =>  1,
        ]);
        ?>
    </div>
</div>