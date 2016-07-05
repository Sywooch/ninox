<?php
use yii\bootstrap\Html;

$css = <<< 'CSS'
.datepicker{
    z-index: 10000 !important;
}
CSS;

$this->registerCss($css);


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
    echo Html::tag('h3', $type->type),
    \kartik\grid\GridView::widget([
        'summary'       =>  false,
        'id'            =>  'costs_'.$type->type,
        'dataProvider'  =>  new \yii\data\ActiveDataProvider([
            'query' =>  $type->getCosts(),
            'sort'  =>  [
                'defaultOrder'   =>  [
                    'date'  =>  SORT_DESC
                ]
            ]
        ]),
        'pjax'  =>  true,
        'columns'       =>  [
            [
                'attribute' =>  'date',
                'width'     =>  '130px',
                'value'     =>  function($model){
                    return \Yii::$app->formatter->asDate($model->date);
                }
            ],
            [
                'attribute' =>  'costSumm',
                'width'     =>  '130px',
                'value'     =>  function($model){
                    return "{$model->costSumm} грн.";
                }
            ],
            'costComment'
        ]
    ]);
}

echo $remodal->renderModal($this->render('_add_modal', [
    'model' =>  $costForm
]));