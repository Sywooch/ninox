<?php
use kartik\form\ActiveForm;
use yii\bootstrap\Modal;

$model = new \common\models\Banner();

Modal::begin([
    'header' => '<h2>Добавить новый баннер</h2>',
    'options'   =>  [
        'style' =>  'color: black'
    ],
    'toggleButton' => [
        'label'     =>  '<i class="glyphicon glyphicon-plus"></i>&nbsp;Добавить новый баннер',
        'class'     =>  'btn btn-default'
    ],
    'size'  =>  Modal::SIZE_DEFAULT,
]);
$form = ActiveForm::begin([]);
?>
<?=$form->field($model, 'banner')?>
<?php
$form->end();
Modal::end();
?>