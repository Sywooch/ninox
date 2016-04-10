<?php
use yii\bootstrap\Html;

$this->title = 'Расходы';
$this->params['breadcrumbs'][] = $this->title;

echo Html::tag('h1', $this->title);

foreach($types as $type){
    echo Html::tag('h3', $type['type']),
    \kartik\grid\GridView::widget([
        'summary'       =>  false,
        'dataProvider'  =>  $costFilter->search(['costId' => $type['id']])
    ]);
}

//echo GridView