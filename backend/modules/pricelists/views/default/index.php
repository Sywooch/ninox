<?php
use backend\modules\pricelists\models\PriceListForm;
use rmrevin\yii\fontawesome\FA;

$this->title = 'Прайсы';

$this->params['breadcrumbs'][] = $this->title;

$modal = new \bobroid\remodal\Remodal([
    'id'                    =>  'addPriceList',
    'addRandomToID'         =>  false,
    'confirmButtonOptions'  =>  [
        'label' =>  'Сохранить',
        'type'  =>  'submit',
        'id'    =>  'submitForm'
    ]
]);

$js = <<<'JS'
var removePrice = function(priceListID){
    $.ajax({
        type: 'POST',
        url: '/pricelists/remove',
        data: {
            'priceListID': priceListID
        },
        success: function(data){
            $.pjax.reload({container: '#priceLists-pjax'});
        }
    });
};

$(document).on('pjax:complete', function() {
    $(".remove").on('click', function(e){
        removePrice(e.currentTarget.getAttribute('data-attribute-id'));
    });
});

$(".remove").on('click', function(e){
    removePrice(e.currentTarget.getAttribute('data-attribute-id'));
});
JS;

$this->registerJs($js);

$priceListForm = new PriceListForm();

?>
<h1><?=$this->title?> <small><a href="#addPriceList" class="btn btn-link btn-small"><?=\rmrevin\yii\fontawesome\FA::icon('plus')?> добавить</a></small></h1>
<?=\kartik\grid\GridView::widget([
    'dataProvider'  =>  $pricelists,
    'id'            =>  'priceLists',
    'pjax'          =>  true,
    'summary'       =>  false,
    'columns'       =>  [
        [
            'attribute' =>  'name'
        ],
        [
            'attribute' =>  'format',
            'width'     =>  '100px;',
            'value'     =>  function($model) use(&$priceListForm){
                return $priceListForm->getFormats()[$model->format];
            }
        ],
        [
            'attribute' =>  'creator',
            'format'    =>  'raw',
            'width'     =>  '200px;',
            'value'     =>  function($model){
                $creator = \common\models\Siteuser::getUser($model->creator);
                return \yii\helpers\Html::tag('span', $creator->name, [
                    'data-toggle'   =>  'tooltip',
                    'title'         =>  $creator->username,
                    'style'         =>'text-decoration: underline; cursor:pointer;'
                ]);
            }
        ],
        [
            'attribute' =>  'categories',
            'width'     =>  '160px;',
            'value'     =>  function($model){
                return count($model->categories).' категорий';
            }
        ],
        [
            'class'     =>  \kartik\grid\ActionColumn::className(),
            'buttons'   =>  [
                'view'      =>  function($key, $model){
                    return \yii\helpers\Html::a(FA::i('eye'), \Yii::$app->params['frontend'].'/autopricelist/'.$model->id, [
                        'class' =>  'btn btn-sm btn-default',
                        'target'    =>  '_blank'
                    ]);
                },
                'update'      =>  function($key, $model){
                    return \yii\helpers\Html::button(FA::i('edit'), [
                        'class' =>  'btn btn-sm btn-default '
                    ]);
                },
                'delete'    =>  function($key, $model){
                    return \yii\helpers\Html::button(FA::i('trash'), [
                        'class' =>  'btn btn-sm btn-danger remove',
                        'data-attribute-id' =>  $model->id
                    ]);
                }
            ],
            'template'  =>  \yii\helpers\Html::tag('div', '{view}{update}{delete}', ['class' => 'btn-group btn-group-sm']),
            'width'     =>  '120px;',
        ],
    ]
])?>

<?=$modal->renderModal($this->render('_addPriceList', [
    'model' =>  $priceListForm
]))?>