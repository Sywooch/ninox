<?php
$form = new \yii\bootstrap\ActiveForm();

echo $form->field($model, 'name');
echo $form->field($model, 'code');
echo $form->field($model, 'barcode');