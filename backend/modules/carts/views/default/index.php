<?php

$this->title = 'Клиентские корзины';

$this->params['breadcrumbs'][] = $this->title;

?>
<h1>Клиентские корзины</h1>
<?=\kartik\grid\GridView::widget([
    'dataProvider'  =>  $dataProvider,
    'columns'       =>  [
        [
            'attribute' =>  'cartCode',
            'format'    =>  'html',
            'value'     =>  function($model){
                return \yii\helpers\Html::a($model->cartCode, '/carts/view/'.$model->cartCode);
            }
        ],
        [
            'attribute'  =>  'date'
        ]
    ]
])?>