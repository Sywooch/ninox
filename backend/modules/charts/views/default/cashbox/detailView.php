<?php
use kartik\grid\GridView;

/** @var \backend\modules\charts\models\CashboxDayOperation $model */

$day = \Yii::$app->formatter->asDate($day);

echo \yii\helpers\Html::tag('h3', "Статистика кассы за {$day}"),
GridView::widget([
    'pjax'          =>      true,
    'dataProvider'  =>  $dataProvider,
    'summary'       =>  false,
    'bordered'      =>  false,
    'rowOptions'    =>  function($model){
        $class = '';

        switch($model->operation){
            case $model::OPERATION_SELL:
            case $model::OPERATION_SELF_DELIVERY:
                $class = 'success';
                break;
            case $model::OPERATION_SPEND:
            case $model::OPERATION_TAKE:
                $class = 'danger';
                break;
            case $model::OPERATION_PUT:
                $class = 'info';
                break;
        }

        return ['class' => $class];
    },
    'columns'       =>  [
        [
            'attribute' =>  'date',
            'label'     =>  'Время',
            'width'     =>  '60px',
            'hAlign'    =>  GridView::ALIGN_CENTER,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'value'     =>  function($model){
                return \Yii::$app->formatter->asTime($model->date, 'php:H:i');
            }
        ],
        [
            'attribute' =>  'order',
            'label'     =>  'Заказ',
            'format'    =>  'html',
            'width'     =>  '60px',
            'hAlign'    =>  GridView::ALIGN_CENTER,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'value'     =>  function($model){
                if(empty($model->order) || empty($model->orderModel)){
                    return '';
                }

                return \yii\helpers\Html::a($model->orderModel->number, '/orders/showorder/'.$model->order);
            }
        ],
        [
            'attribute' =>  'operation',
            'label'     =>  'Тип',
            'hAlign'    =>  GridView::ALIGN_CENTER,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'value'     =>  function($model){
                if(!array_key_exists($model->operation, $model->types)){
                    return 'Неизвестная операция';
                }

                return $model->types[$model->operation];
            }
        ],
        [
            'attribute' =>  'sum',
            'label'     =>  'Сумма',
            'hAlign'    =>  GridView::ALIGN_CENTER,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'value'     =>  function($model){
                $sign = '';

                if($model->operation == $model::OPERATION_TAKE || $model->operation == $model::OPERATION_SPEND){
                    $sign = '-';
                }

                return "{$sign}{$model->amount} грн.";
            }
        ],
        [
            'label'     =>  'Остаток',
        ],
        [
            'attribute' =>  'responsibleUser',
            'label'     =>  'Пользователь',
            'hAlign'    =>  GridView::ALIGN_CENTER,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'value'     =>  function($model){
                if(empty($model->responsibleUserModel)){
                    return 'Неизвестный пользователь';
                }

                return $model->responsibleUserModel->name;
            }
        ],

    ]
])?>