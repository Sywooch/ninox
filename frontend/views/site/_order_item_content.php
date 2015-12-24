<div class="content-data-body-first">
    <?=$form->field($model, 'customerName'),
    $form->field($model, 'customerSurname'),
    $form->field($model, 'deliveryCity'),
    $form->field($model, 'deliveryRegion'),
    $form->field($model, 'customerEmail')?>
    <div class="clearfix"></div>
    <div class="next-button">
        <?php
        echo \yii\helpers\Html::button('Далее', [
            'class' =>  'button goToPage',
            'type'  =>  'button',
	        'data-page' =>  1
        ]);
        ?>
    </div>
</div>