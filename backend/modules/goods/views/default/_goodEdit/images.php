<?php
use kartik\file\FileInput;
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\Html;
use yii\helpers\Url;

$css = <<<'CSS'
.file-preview{
    border: none;
    border-radius: 0;
    padding: 0;
    margin: 0;
}

.file-preview .file-drop-zone{
    border: none;
    border-radius: 0;
    margin: 0;
    padding: 0;
}

.file-preview .file-footer-caption{
    width: 152px;
}

.file-preview .file-preview-image{
    max-height: 100px;
}

.file-preview .file-drop-zone.file-highlighted {
    border: 0 !important;
    outline: 2px dashed #999 !important;
}
CSS;

$this->registerCss($css);

//TODO: нужно поправить здесь так, чтобы картинки ровно показывались

$form = new \yii\bootstrap\ActiveForm();

$photos = $previewPhotos = [];

foreach($good->photos as $photo){
    $previewPhotos[] = \yii\bootstrap\Html::img('http://krasota-style.com.ua/img/catalog/'.$photo->ico, [
        'class' =>  'file-preview-image'
    ]);
    $photos[] = [
        'caption'   =>  $photo->ico,
        'key'       =>  $photo->id, //TODO: Заменить на другое значение ключа
        'extra'     =>  [
            'order' =>  $photo->order
        ],
        'frameAttr' =>  [
            'style'     =>  'max-height: 80px; max-width: 80px;'
        ]
    ];
}

\Yii::trace(\yii\helpers\Json::encode($photos));

echo FileInput::widget([
    'name' => 'input-ru[]',
    'language' => \Yii::$app->language,
    'options' => [
        'multiple' => true
    ],
    'pluginOptions' => [
        'initialPreview'        =>  $previewPhotos,
        'initialPreviewConfig'  =>  $photos,
        'overwriteInitial'      =>  false,
        'uploadExtraData'       =>  [
            'goodID'            =>  $good->ID
        ],
        'layoutTemplates'       =>  [
            'actions'           =>  '<div class="file-actions">
<div class="file-footer-buttons">{upload}{delete}'.Html::button(FA::i('star'), ['class' => 'btn btn-default btn-xs', 'style' => 'color: #ffcc00']).'</div>
<div class="file-upload-indicator" tabindex="-1" title="{indicatorTitle}">{indicator}</div>
<div class="clearfix"></div>
</div>',
        ],
        'showCaption'           =>  false,
        'showUpload'            =>  false,
        'showRemove'            =>  false,
        'showClose'             =>  false,
        'previewFileType'       =>  'image',
        'deleteUrl'             =>  Url::toRoute(['/goods/photo', 'act' => 'delete']),
        'uploadUrl'             =>  Url::toRoute(['/goods/photo', 'act' => 'upload']),
    ]
]);