<?php
use yii\widgets\ListView;

$this->title = 'Баннеры';

$this->params['breadcrumbs'][] = $this->title;


?>
<h1>Баннеры</h1>
<div class="btn-group">
    <?=\app\components\AddBannerGroupWidget::widget([])?>
    <?=\app\components\AddBannerWidget::widget([])?>

    <a href="/admin/banners/stats" class="btn btn-default"><i class="glyphicon glyphicon-stats"></i>&nbsp;Статистика баннеров</a></div>
    <br><br>
<?=ListView::widget([
    'dataProvider'  => $banners,
    'itemOptions'   => [
        'class'     => 'list-group-item',
        'tag'       =>  'li'
    ],
    'layout'        =>  '<div class="row"><div class="col-xs-12">{summary}</div><div class="col-xs-12"><ul class="list-group">{items}</ul></div><div class="col-xs-12"><center>{pager}</center></div></div>',
    'summary'       =>  '',
    'pager'         =>  [

    ],
    'viewParams'    =>  [
        'class' =>  'list-group-item',
        'tag'   =>  'li'
    ],
    'itemView' => function ($model, $key, $index, $widget) {
        return $this->render('_banner_list_item', [
            'model' =>  $model
        ]);
    },
])?>