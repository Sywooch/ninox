<?php
use kartik\file\FileInput;
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = 'Импорт прайслистов';

$this->params['breadcrumbs'][] = $this->title;

\bobroid\sweetalert\SweetalertAsset::register($this);

$js = <<<'JS'
$('#fileInput').on('fileuploaded', function(event, data, previewId, index) {
    console.log(data);
    swal({
        title: "Загрузка файла",
        text: "Введите название для файла",
        html: true,
        type: "input",
        showCancelButton: false,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Сумма к оплате",
        inputValue: data.response.name,
        confirmButtonText: 'Сохранить'
    },
    function(inputValue){
        if(inputValue != data.response.name){
            $.ajax({
                type: 'POST',
                url: '/goods/import',
                data: {
                    action: 'renameFile',
                    id: data.response.id,
                    value: inputValue
                }
            });
        }

        swal.close();

        $.pjax.reload({container: '#uploadedPricelists-pjax'});
    });
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
    'dataProvider'  =>  $priceListsProvider,
    'pjax'          =>  true,
    'summary'       =>  false,
    'columns'       =>  [
        [
            'attribute' =>  'name'
        ],[
            'attribute' =>  'format'
        ],[
            'attribute' =>  'created'
        ],[
            'attribute' =>  'creator',
            'format'    =>  'raw',
            'value'     =>  function($model){
                $user = \common\models\Siteuser::getUser($model->creator);

                if($user){
                    return Html::tag('span', $user->name, [
                        'data-toggle'   =>  'tooltip',
                        'data-content'  =>  $user->username
                    ]);
                }
            }
        ],[
            'class'     =>  \kartik\grid\ActionColumn::className(),
            'width'     =>  '120px',
            'buttons'   =>  [
                'view'  =>  function($key, $model){
                    return Html::a(FA::i('eye'), '?fileid='.$model->id, ['class' => 'btn btn-default', 'data-pjax' => 0]);
                },
                'download'  =>  function($key, $model){
                    return Html::a(FA::i('download'), "/files/importedPrices/".$model->file, [
                        'class'     => 'btn btn-default',
                        'data-pjax' =>  0
                    ]);
                },
                'remove'  =>  function($key, $model){
                    return Html::button(FA::i('trash'), ['class' => 'btn btn-default', 'data-pjax' =>  0, 'data-attribute' => $model->id]);
                },
            ],
            'template'   =>  '<div class="btn-group btn-group-sm">{view}{download}{remove}</div>'
        ]
    ]
]);