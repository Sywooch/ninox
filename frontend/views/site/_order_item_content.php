<div class="content-data-body-first">
    <?=$form->field($model, 'customerName'),
    $form->field($model, 'customerSurname'),
    $form->field($model, 'deliveryCity'),
    $form->field($model, 'deliveryRegion')->widget(\kartik\select2\Select2::className(), [
        'data' => $model->regions,
        'language' => \Yii::$app->language,
        'options' => ['placeholder' => \Yii::t('shop', 'Выберите область...')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]),
    $form->field($model, 'customerEmail')?>
    <div class="next-button">
        <?=\yii\helpers\Html::button('Далее', [
            'class'     =>  'button yellow-button middle-button goToPage',
            'type'      =>  'button',
	        'data-page' =>  1,
        ]);?>
    </div>
</div>