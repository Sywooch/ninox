<?php
use yii\bootstrap\Html;

echo \kartik\grid\GridView::widget([
    'dataProvider'  =>  $orders,
    'summary'       =>  false,
    'columns'       =>  [
        'number',
        [
            'attribute' =>  'customerName',
            'value'     =>  function($model){
                return $model->customerName.' '.$model->customerSurname;
            }
        ],
        [
            'attribute' =>  'customerPhone',
            'value'     =>  function($model){
                return \Yii::$app->formatter->asPhone($model->customerPhone);
            }
        ],
        [
            'attribute' =>  'deliveryRegion',
            'format'    =>  'html',
            'value'     =>  function($model){
                return Html::tag('div', Html::tag('b', $model->deliveryCity)).Html::tag('div', Html::tag('small', $model->deliveryRegion));
            }
        ],
        [
            'attribute' =>  'paymentType',
            'value'     =>  function($model){
                return $model->paymentTypeModel->description; //TODO: Сделать это через relation, чтоб оно не тащилось для каждого заказа отдельно
            }
        ],
        'actualAmount'
    ]
]);