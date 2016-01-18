<?php
use backend\models\CashboxForm;

$this->title = $model->name;

$this->params['breadcrumbs'][] = [
    'url'   =>  '/store',
    'label' =>  'Магазины и склады'
];

$this->params['breadcrumbs'][] = $this->title;

$js = <<<'JS'
JS;

\backend\assets\InputAreaAsset::register($this);

?>
<h1><?=$this->title?> <small><?=$model->type == $model::TYPE_WAREHOUSE ? 'склад' : 'магазин'?></small></h1>
<h2 style="margin-bottom: 5px">Товары</h2>
<div class="well well-sm">

</div>

<h2 style="margin-bottom: 5px">Пользователи</h2>
<div class="well well-sm">

</div>

<?php if($model->type != $model::TYPE_WAREHOUSE){ ?>
<h2 style="margin-bottom: 5px">Кассы<small><a class="btn btn-link" href="#addCashbox"><?=\rmrevin\yii\fontawesome\FA::icon('plus')?> добавить</a></small></h2>
<div class="well well-sm">
    <?=\kartik\grid\GridView::widget([
        'dataProvider'  =>  $cashboxesDataProvider
    ])?>
</div>

    <?php
    $addCashboxModal = new \bobroid\remodal\Remodal([
        'addRandomToID'     =>  false,
        'id'                =>  'addCashbox',
        'confirmButton'     =>  false,
        'cancelButton'      =>  false,
    ]);

    echo $addCashboxModal->renderModal($this->render('_addCashboxModal', [
        'model'     =>  new CashboxForm
    ]));
    ?>
<?php } ?>