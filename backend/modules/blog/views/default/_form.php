<?php

use bobroid\imperavi\Widget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\modules\blog\controllers\LinkController;
use kartik\file\FileInput;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Articles */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="articles-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>


    <?php if(!$model->isNewRecord) {?>
    <label class="control-label" for="articles-content">Текущая картинка</label>
    <img width="100%" src="<?= LinkController::getForImg().$model->ico ?>">
    <?php } ?>
    <div class="form-group field-articles-future_publish">
        <?= $form->field($model, 'ico')->input('hidden') ?>
        <?=FileInput::widget([
            'name'  =>  'ArticlesPhoto[ico]',
            'options'=>[
                'accept' => 'image/*'
            ],
            'pluginOptions' => [
                'uploadUrl' =>  '/admin/blog/uploadnewphoto',
                'uploadExtraData' => [
                    'title' => 'temp'
                ],
                'showCaption' => false,
                'showRemove' => false,
                'showUpload' => true,
                'showPreview' => false,
                'uploadClass'   =>  'btn btn-info',
                'browseClass' => 'btn btn-primary btn-block',
                'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                'browseLabel' =>  'Выбрать фото',
                'uploadLabel' =>  'Загрузить',
                'layoutTemplates'   =>  [
                    'main1' =>  '{preview}\n<div class="kv-upload-progress hide"></div>\n<div class="input-group {class}">\n{caption}\n
                                                 <div class="input-group-btn">\n{remove}\n{cancel}\n{browse}\n{upload}\n</div>\n</div>',
                    'main2' =>  '{preview} <div class="kv-upload-progress hide"></div><div class="row"><div class="col-xs-3">{browse}</div><div class="col-xs-4" style="margin-left: -17px;">{upload}</div></div>'
                ],
            ],
            'pluginEvents'  =>  [
                'fileuploaded'   =>  'function(event, data, previewId, index){
                                                alert(\'her\');
                                             }',
            ],
        ])?>
    </div>

    <?= $form->field($model, 'title')->textarea(['rows' => 1]) ?>

    <label class="control-label" for="articles-content">Текст</label>
    <?=Widget::widget([
        'model' => $model,
        'attribute' => 'content',
        'options' => [
            'toolbar' => true,
            'imageUpload' => '/admin/blog/uploadbodyphoto'
        ],
    ]);?>

    <?= $form->field($model, 'description')->textarea(['rows' => 2]) ?>

    <?= $form->field($model, 'keywords')->textarea(['rows' => 6]) ?>

    <?php if(!$model->isNewRecord) {?>
    <?= $form->field($model, 'mod')->textInput(['disabled' => true]) ?>

    <?= $form->field($model, 'rate')->textInput() ?>

    <?= $form->field($model, 'views')->textInput() ?>
    <?}?>

    <div class="form-group field-articles-future_publish">
        <label class="control-label" for="articles-future_publish">Дата публикации</label>
        <?= DateTimePicker::widget([
            'name' => 'Articles[future_publish]',
            'options' => [
                'placeholder' => 'Выберите время',
                'id' => 'articles-future_publish'
            ],
            'language' => 'ru',
            'convertFormat' => true,
            'pluginOptions' => [
                'format' => 'y-m-d H:i:s',
                'todayHighlight' => true
            ]
        ]);
        ?>
    </div>

    <?= $form->field($model, 'video')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
