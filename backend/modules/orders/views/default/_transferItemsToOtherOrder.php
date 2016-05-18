<?php
use backend\models\History;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;

Modal::begin([
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
    'id'        =>  'mergeOrdersTable',
    'summary'  =>  false,
    'hover'     =>  true,
    'striped'   =>  true,
    'rowOptions'    =>  function($model){
        return [
            'class' =>  'orderRow',
            'data-attribute-orderNumber'    =>  $model->number,
        ];
    },
    'bordered'  =>  false,
    'columns'   =>  [
        [
            'attribute' =>  'id',
            'value'     =>  function($model){
                return $model->number;
            }
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