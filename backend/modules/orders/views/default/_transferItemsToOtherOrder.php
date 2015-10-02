<?php
use app\models\History;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;

Modal::begin([
    'header' => 'asdf',
    'options'   =>  [
        'style' =>  'color: black'
    ],
    'toggleButton' => [
        'label'     =>  'Переместить товары в другой заказ',
        'class'     =>  'btn btn-default',
        'style'     =>  'margin-top: 8px; margin-right: 5px'
    ],
    'size'  =>  Modal::SIZE_LARGE,
]);
Pjax::begin();
echo \kartik\grid\GridView::widget([
    'dataProvider'  =>  History::ordersDataProvider([
        'thisOrder'  =>  $order->id
    ]),
    'summary'  =>  '',
    'hover'     =>  true,
    'striped'   =>  true,
    'rowOptions'    =>  [
        'class' =>  'orderRow',
        'data-attribute-itemID'    =>  ''
    ],
    'bordered'  =>  false,
    'columns'   =>  [
        [
            'attribute' =>  'id'
        ],
        [
            'attribute' =>  'customerName',
            'value'     =>  function($model){
                return $model->customerSurname.' '.$model->customerName.' '.$model->customerFathername;
            }
        ],
        [
            'attribute' =>  'customerPhone'
        ],
        [
            'attribute' =>  'customerEmail'
        ],
        [
            'attribute' =>  'deliveryCity',
            'value'     =>  function($model){
                $r = '';
                $r .= $model->deliveryCity;
                $r .= !empty($model->deliveryRegion) ? ', '.$model->deliveryRegion : '';

                return $r;
            }
        ],
    ]
]);
Pjax::end();
Modal::end();
?>