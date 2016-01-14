<?php
$this->title = $model->name;

$this->params['breadcrumbs'][] = [
    'url'   =>  '/store',
    'label' =>  'Магазины и склады'
];

$this->params['breadcrumbs'][] = $this->title;

?>
<h1><?=$this->title?> <small><?=$model->type == $model::TYPE_WAREHOUSE ? 'склад' : 'магазин'?></small></h1>
<h2 style="margin-bottom: 5px">Товары</h2>
<div class="well well-sm">

</div>

<h2 style="margin-bottom: 5px">Пользователи</h2>
<div class="well well-sm">

</div>

<?php if($model->type != $model::TYPE_WAREHOUSE){ ?>
<h2 style="margin-bottom: 5px">Кассы</h2>
<div class="well well-sm">
    <?=\kartik\grid\GridView::widget([
        'dataProvider'  =>  $cashboxesDataProvider
    ])?>
</div>
<?php } ?>