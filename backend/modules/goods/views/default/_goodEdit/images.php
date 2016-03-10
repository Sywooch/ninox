<?php
use kartik\file\FileInput;
use yii\bootstrap\Html;
use yii\helpers\Url;

\kartik\sortable\SortableAsset::register($this);

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

.sortable-placeholder{
    display: table;
    width: 160px;
    border-radius: 3px;
    outline: 1px #ddd dashed;
    margin-top: 10px;
    margin-bottom: -10px;
    float: left;
    text-align: center;
    vertical-align: middle;
}

.file-preview .file-preview-thumbnails .file-preview-frame:first-child{
    outline: 2px solid rgba(117,187,253, 0.5);
}

CSS;

$this->registerCss($css);

$photos = $previewPhotos = [];

foreach($good->photos as $photo){
    $previewPhotos[] = \yii\bootstrap\Html::img('http://krasota-style.com.ua/img/catalog/'.$photo->ico, [
        'class'         =>  'file-preview-image',
        'data-itemID'   =>  $good->ID,
        'data-order'    =>  $photo->order
    ]);
    $photos[] = [
        'caption'   =>  'http://krasota-style.com.ua/img/catalog/'.$photo->ico,
        'key'       =>  $photo->itemid,
        'extra'     =>  [
            'order' =>  $photo->order
        ],
        'frameAttr' =>  [
            'style'     =>  'max-height: 80px; max-width: 80px;'
        ]
    ];
}

echo FileInput::widget([
    'name' => 'goodPhoto[]',
    'language' => \Yii::$app->language,
    'options' => [
        'multiple' => true
    ],
    'pluginOptions' => [
        'initialPreview'        =>  $previewPhotos,
        'initialPreviewConfig'  =>  $photos,
        'uploadExtraData'       =>  [
            'key'               =>  $good->ID
        ],
        'showCaption'           =>  false,
        'showRemove'            =>  false,
        'showClose'             =>  false,
        'overwriteInitial'      =>  false,
        'previewFileType'       =>  'image',
        'deleteUrl'             =>  Url::toRoute(['/goods/photo', 'act' => 'delete']),
        'uploadUrl'             =>  Url::toRoute(['/goods/photo', 'act' => 'upload']),
    ]
]);

$js = <<<'JS'
$(".file-preview-thumbnails").sortable({
    forcePlaceholderSize: true,
    items: '.file-preview-frame',
    placeholderClass: 'imagesPlaceholder',
    showHandle: true,
    type: 'grid'
}).bind('sortupdate', function(e, ui){
    var firstImage = $(".file-preview-thumbnails:first-child");

    $("#goodMainPhoto")[0].src = firstImage.find("img")[0].src;

    var a = $(".file-preview-thumbnails div img"),
        items = new Array(),
        good = null;


    for(var i = 0; i < a.length; i++){
        items.push(a[i].getAttribute("data-order"));
        good = a[i].getAttribute("data-itemID");
        a[i].setAttribute("data-order", i + 1);
    }

    $.ajax({
		type: 'POST',
		url: '/goods/photo?act=reorder',
		data: {
		    items: items,
		    key: good
		}
	});
});


JS;

$this->registerJs($js);
