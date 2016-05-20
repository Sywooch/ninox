<?php

$this->title = 'Корзина '.$cart->cartCode;

$css = <<<'CSS'
.img-thumbnail{
    max-width: 160px;
}
CSS;

$this->registerCss($css);

$this->params['breadcrumbs'][] = [
    'label' =>  'Клиентские корзины',
    'url'   =>  '/carts'
];

$this->params['breadcrumbs'][] = $this->title;

echo \yii\bootstrap\Html::tag('h1',
    $this->title.'&nbsp;'.
    \yii\bootstrap\Html::a(
        \rmrevin\yii\fontawesome\FA::i('upload'),
        \Yii::$app->params['frontend'].
        '/?secretKey='.\Yii::$app->params['secretAdminPanelKey'].
        '&cartCode='.$cart->cartCode
    )
);

echo \kartik\grid\GridView::widget([
    'dataProvider'  =>  $dataProvider,
    'summary'       =>  false,
    'columns'       =>  [
        [
            'class' =>  \kartik\grid\SerialColumn::className()
        ],
        [
            'format'=>  'html',
            'width' =>  '160px',
            'value' =>  function($model){
                return \yii\bootstrap\Html::img(\Yii::$app->params['cdn-link'].'/img/catalog/'.$model->good->photo, ['class' => 'img-thumbnail']);
            }
        ],
        [
            'label' =>  'Товар',
            'value' =>  function($model){
                return $model->good->name;
            }
        ],
        [
            'attribute' =>  'count',
            'label'     =>  'Колличество'
        ],
        [
            'attribute' =>  'date',
            'label'     =>  'Дата'
        ]
    ]
]);