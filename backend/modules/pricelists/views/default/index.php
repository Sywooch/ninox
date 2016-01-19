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
            'attribute' =>  'format'
        ],
        [
            'attribute' =>  'creator'
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
    'model' =>  new PriceListForm()
]))?>