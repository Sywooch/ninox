<?php
use kartik\file\FileInput;
use yii\helpers\Url;

$css = <<<'CSS'
.file-preview-image{
    max-width: 100px;
    max-height: 100px;
}
CSS;

$this->registerCss($css);

//TODO: нужно поправить здесь так, чтобы картинки ровно показывались

$form = new \yii\bootstrap\ActiveForm();

$photos = [];

foreach($good->photos as $photo){
    $previewPhotos[] = \yii\bootstrap\Html::img('http://krasota-style.com.ua/img/catalog/'.$photo->ico, [
        'class' =>  'file-preview-image'
    ]);
    $photos[] = [
        'caption'   =>  $photo->ico,
        'url'       =>  '/goods/photo&act=delete',
        'key'       =>  $photo->id, //TODO: Заменить на другое значение ключа
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
        'previewFileType'       =>  'any',
        'uploadUrl'             =>  Url::to(['/site/file-upload']),
    ]
]);