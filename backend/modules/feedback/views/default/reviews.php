<?php
use yii\widgets\ListView;

$this->title = 'Баннеры';

$this->params['breadcrumbs'][] = $this->title;


?>
<h1>Отзывы</h1>
<div class="btn-group">

    <br><br>
    <?=ListView::widget([
        'dataProvider'  => $reviews,
        'itemOptions'   => [
            'class'     => 'col-xs-6',
            'tag'       =>  'div'
        ],
        'layout'        =>  '<div class="row"><div class="col-xs-12">{summary}</div><div class="col-xs-12"><div class="row">{items}</div></div><div class="col-xs-12"><center>{pager}</center></div></div>',
        'summary'       =>  '',
        'itemView' => function ($model, $key, $index, $widget) {
            return $this->render('_review_item', [
                'model' =>  $model
            ]);
        },
    ])?>

