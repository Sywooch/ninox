<div class="content-data-body-first">
    <?=$form->field($model, 'customerName'),
    $form->field($model, 'customerSurname'),
    $form->field($model, 'deliveryCity'),
    $form->field($model, 'deliveryRegion'),
    $form->field($model, 'customerEmail')?>
    <div class="next-button">
        <?php
        echo \yii\helpers\Html::button('Далее', [
            'class' =>  'button',
            'type'  =>  'button',
            'data-toggle' => "collapse",
            'data-target' =>  "#collapse2",
            'data-parent'   =>  '#accordion'
        ]);
        ?>
    </div>
</div>