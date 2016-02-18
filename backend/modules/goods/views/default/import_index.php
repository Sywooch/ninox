<?php
use kartik\file\FileInput;
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = 'Импорт прайслистов';

$this->params['breadcrumbs'][] = $this->title;

$js = <<<'JS'
$('#fileInput').on('fileuploaded', function(event, data, previewId, index) {
    console.log('File uploaded triggered');
});
JS;

$this->registerJs($js);

echo Html::tag('h1', $this->title);

echo Html::tag('div', FileInput::widget([
    'name'          =>  'pricelist',
    'id'            =>  'fileInput',
    'options'       =>  [
        'multiple'      =>  true
    ],
    'pluginOptions' =>  [
        'uploadUrl'         =>  Url::to(['/goods/import']),
        'showCaption'       => false,
        'showRemove'        => false,
        'showUpload'        => false,
        'dropZoneEnabled'   => false,
        'allowedFileExtensions'  =>  [
            'xls', 'xlsx'
        ],
        'browseLabel'       =>  'Добавить',
        'browseIcon'        =>  FA::i('plus').' &nbsp;',
        'browseClass'       =>  'btn btn-default',
    ]
]), [
    'class' =>  'btn-group',
    'id'    =>  'fileInputOverlay',
    'style' =>  'margin-bottom: 10px;'
]);

echo \kartik\grid\GridView::widget([
    'id'            =>  'uploadedPricelists',
    'dataProvider'  =>  $priceListsProvider
]);