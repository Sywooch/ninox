<?php
use common\models\Category;
use kartik\form\ActiveForm;
use kartik\touchspin\TouchSpin;
use yii\helpers\Html;

$form = new ActiveForm([
    'id' => 'login-form-horizontal',
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]
]);

echo $form->field($model, 'name');
echo $form->field($model, 'code');
echo $form->field($model, 'category')->widget(\kartik\select2\Select2::className(), [
    'data' => Category::getList(),
    'options' => [
        'placeholder' => 'Выберите категорию...'
    ],
    'pluginOptions' => [
        'allowClear' => false
    ],
]);
echo $form->field($model, 'barcode');
echo $form->field($model, 'description')->widget(\bobroid\imperavi\Widget::className(), [
    'model' => $model,
    'attribute' => 'description',
    'options' => [
        'toolbar' => true,
        /*'autosave'  =>  '#',
        'autosaveInterval'  =>  '5',*/
        'imageUpload' => '/upload.php',
        'imageManagerJson' => '/images/images.json'
    ],
    'plugins' => [
        'fullscreen',
        'imagemanager',
        'fontcolor',
        'fontsize',
        'table',
    ]
]);

echo $form->field($model, 'barcode');
echo $form->field($model, 'wholesalePrice');
echo $form->field($model, 'retailPrice');
echo $form->field($model, 'inPackageAmount', [
    'template'      =>  '{label}<div style="margin-left: -15px;" class="col-xs-8">{input}'.$form->field($model, 'undefinedPackageAmount', ['options' => ['class' => 'col-xs-3']])->checkbox(['label' => 'неизвестно']).'</div>',
    'inputOptions'  =>  [
        'class' =>  'col-xs-6'
    ]
])->widget(TouchSpin::classname(), [
    'options'   =>  [
        'disabled'  =>  empty($model->inPackageAmount)
    ],
    'pluginOptions' =>  [
        'max'   =>  10000
    ]
]);