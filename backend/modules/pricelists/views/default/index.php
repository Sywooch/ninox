<?php
use backend\modules\pricelists\models\PriceListForm;

$this->title = 'Прайсы';

    $this->params['breadcrumbs'][] = $this->title;

    $modal = new \bobroid\remodal\Remodal([
        'id'            =>  'addPriceList',
        'addRandomToID' =>  false,
    ]);
?>
<h1><?=$this->title?> <small><a href="#addPriceList" class="btn btn-link btn-small"><?=\rmrevin\yii\fontawesome\FA::icon('plus')?> добавить</a></small></h1>
<?=\kartik\grid\GridView::widget([
    'dataProvider'  =>  $pricelists
])?>

<?=$modal->renderModal($this->render('_addPriceList', [
    'model' =>  new PriceListForm()
]))?>