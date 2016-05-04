<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 08.12.15
 * Time: 17:31
 */

$model = (new \frontend\modules\account\models\CustomerInfoForm());

$form = \kartik\form\ActiveForm::begin();
echo $form->field($model, 'name'),
    $form->field($model, 'surname'),
    $form->field($model, 'phone'),
    $form->field($model, 'email');

echo \yii\bootstrap\Html::button(\Yii::t('shop', 'Сохранить'), ['type' => 'submit']);

$form->end();