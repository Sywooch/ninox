<?php
use kartik\file\FileInput;
use yii\bootstrap\Html;
use yii\helpers\Url;

//\kartik\sortable\SortableAsset::register($this);

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
    $previewPhotos[] = \yii\bootstrap\Html::img(\Yii::$app->params['cdn-link'].'/img/catalog/'.$photo->ico, [
        'class'         =>  'file-preview-image',
        'data-itemID'   =>  $good->ID,
        'data-order'    =>  $photo->order
    ]);
    $photos[] = [
        'caption'   =>  \Yii::$app->params['cdn-link'].'/img/catalog/'.$photo->ico,
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
    'id'        =>  'photosFileInput',
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
        'fileActionSettings'    =>  [
            'dragSettings'  =>  [
                'forcePlaceholderSize'  =>  true,
                'items'                 =>  '.file-preview-frame',
                'placeholderClass'      =>  'imagesPlaceholder',
                'showHandle'            =>  true,
                'type'                  =>  'grid'
            ]
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
$("#photosFileInput").on('filesorted', function(e){
    $("#goodMainPhoto").attr('src', $(".file-preview-thumbnails:first-child img").prop('src'));

    var a = $(".file-preview-thumbnails div.kv-file-content"),
        items = new Array(),
        good = null;


    $.each(a, function(index, item){
        item = $(item).find('img');
        
        items.push(item.attr('data-order'));
        good = item.attr("data-itemID");
        item.attr("data-order", (index + 1));
    })

    $.ajax({
		type: 'POST',
		url: '/goods/photo?act=reorder',
		data: {
		    items: items,
		    key: good
		}
	});
}).on('fileuploaded', function(event, data, previewId, index){

    var image = $("#" + previewId);
    
    image.find(".file-thumb-progress")
        .addClass('hide');
    
    image.find(".file-upload-indicator")
        .replaceWith('<span class="file-drag-handle drag-handle-init text-info" title="Move / Rearrange"><i class="glyphicon glyphicon-menu-hamburger"></i></span>');
    
    image.find(".kv-file-content img")
        .attr('data-itemid', '123')
        .attr('data-order', ($(".file-preview-thumbnails .file-preview-frame").length + 1));
        
    $(".file-input .file-initial-thumbs").append(image);
    
    $("#photosFileInput").sortable();
}).on('filedeleted', function(){
    $("#goodMainPhoto").attr('src', $(".file-preview-thumbnails:first-child img").prop('src'));
});
JS;

$this->registerJs($js);
