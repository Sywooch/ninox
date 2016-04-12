<?php
use yii\bootstrap\Html;

$this->title = 'Расходы';
$this->params['breadcrumbs'][] = $this->title;

echo Html::tag('h1', $this->title);

$remodal = new \bobroid\remodal\Remodal([
    'id'            =>  'addCostModal',
    'addRandomToID' =>  false,
    'confirmButton' =>  false,
    'cancelButton'  =>  false,
]);

$remodal->buttonOptions = [
    'class' =>  'btn btn-default',
    'label' =>  \rmrevin\yii\fontawesome\FA::i('plus').' Добавить трату'
];

echo $remodal->renderButton();

foreach($types as $type){
    echo Html::tag('h3', $type['type']),
    \kartik\grid\GridView::widget([
        'summary'       =>  false,
        'id'            =>  'costs_'.$type['type'],
        'dataProvider'  =>  $costFilter->search(['costId' => $type['id']]),
        'columns'       =>  [
            'date',
            'costSumm',
            'costComment'
        ]
    ]);
}

echo $remodal->renderModal($this->render('_add_modal', [
    'model' =>  $costForm
]));