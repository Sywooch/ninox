<?php
$form = \kartik\form\ActiveForm::begin();

echo $form->field($model, 'sum'),
    $form->field($model, 'type')->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\CostsType::find()->asArray()->all(), 'id', 'type')),
    $form->field($model, 'date')->widget(\kartik\date\DatePicker::className(), [
        'pluginOptions' =>  [
            'format'    =>  'yyyy-mm-dd'
        ]
    ]),
    $form->field($model, 'comment')->textarea(),
    \yii\helpers\Html::button('Сохранить', ['class' => 'btn btn-default', 'type' => 'submit']);

$form->end();