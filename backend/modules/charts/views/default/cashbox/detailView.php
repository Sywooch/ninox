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

        switch($model->type){
            case $model::TYPE_SHOP_BUY:
            case $model::TYPE_SELF_DELIVERY:
                $class = 'success';
                break;
            case $model::TYPE_CASHBOX_SPEND:
                $class = 'danger';
                break;
            case $model::TYPE_CASHBOX_GET:
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
            'attribute' =>  'orderID',
            'label'     =>  'Заказ',
            'format'    =>  'html',
            'width'     =>  '60px',
            'hAlign'    =>  GridView::ALIGN_CENTER,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'value'     =>  function($model){
                if(empty($model->orderID) || empty($model->order)){
                    return '';
                }

                return \yii\helpers\Html::a($model->order->number, '/orders/showorder/'.$model->orderID);
            }
        ],
        [
            'attribute' =>  'type',
            'label'     =>  'Тип',
            'hAlign'    =>  GridView::ALIGN_CENTER,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'value'     =>  function($model){
                if(!array_key_exists($model->type, $model->types)){
                    return 'Неизвестная операция';
                }

                return $model->types[$model->type];
            }
        ],
        [
            'attribute' =>  'sum',
            'label'     =>  'Сумма',
            'hAlign'    =>  GridView::ALIGN_CENTER,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'value'     =>  function($model){
                $sign = '';

                if($model->type == $model::TYPE_CASHBOX_GET || $model->type == $model::TYPE_CASHBOX_SPEND){
                    $sign = '-';
                }

                return "{$sign}{$model->sum} грн.";
            }
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