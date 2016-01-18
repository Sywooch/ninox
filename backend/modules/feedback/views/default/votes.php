<?php
use common\models;
use yii\widgets\ListView;
$this->title = 'Votes';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1>vote</h1>
<div class="btn-group">

    <br><br>
    <?=ListView::widget([
        'dataProvider'  => $votes,
        'itemOptions'   => [
            'class'     => 'col-xs-6',
            'tag'       =>  'div'
        ],
        'layout'        =>  '<div class="row"><div class="col-xs-12">{summary}</div><div class="col-xs-12"><div class="row">{items}</div></div><div class="col-xs-12"><center>{pager}</center></div></div>',
        'summary'       =>  '',
        'itemView' => function ($model, $key, $index, $widget) {
            return $this->render('_vote_item', [
                'model' =>  $model
            ]);
        },
    ])?>

