<?php
$form = new \kartik\form\ActiveForm();

$form->begin();

echo $form->field($model, 'sum'),
    $form->field($model, 'type')->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\CostsType::find()->asArray()->all(), 'id', 'type')),
    $form->field($model, 'date')->widget(\kartik\date\DatePicker::className(), [
        //'convertFormat' =>  'Y-m-d',
        'pluginOptions' =>  [
            'format'    =>  'yyyy-mm-dd'
        ]
    ]),
    $form->field($model, 'comment')->textarea(),
    \yii\helpers\Html::button('Сохранить', ['class' => 'btn btn-default', 'type' => 'submit']);

$form->end();