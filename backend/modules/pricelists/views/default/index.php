<?php
use backend\modules\pricelists\models\PriceListForm;

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

$css = <<<'CSS'
.remove{
    -webkit-transition: all 0.2s ease-out;
    -moz-transition: all 0.2s ease-out;
    -o-transition: all 0.2s ease-out;
    transition: all 0.2s ease-out;
}

.remove:hover{
    cursor: pointer;
    color: red;
}
CSS;

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

$this->registerCss($css);

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
            'format'    =>  'raw',
            'width'     =>  '20px',
            'value'     =>  function($model){
                return \yii\bootstrap\Html::tag('span', \rmrevin\yii\fontawesome\FA::icon('times'), [
                    'class' =>  'remove',
                    'data-attribute-id' =>  $model->id
                ]);
            }
        ],
        [
            'attribute' =>  'name'
        ],
        [
            'attribute' =>  'format',
            'value'     =>  function($model) use(&$priceListForm){
                return $priceListForm->getFormats()[$model->format];
            }
        ],
        [
            'attribute' =>  'creator',
            'value'     =>  function($model){
                return $model->creator;
            }
        ],
        [
            'attribute' =>  'categories',
            'value'     =>  function($model){
                return count($model->categories).' категорий';
            }
        ],
        [
            'format'    =>  'html',
            'value'     =>  function($model){
                return \yii\helpers\Html::a('Посмотреть', \Yii::$app->params['frontend'].'/autopricelist/'.$model->id);
            }
        ],
    ]
])?>

<?=$modal->renderModal($this->render('_addPriceList', [
    'model' =>  $priceListForm
]))?>