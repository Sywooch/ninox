<?php
echo \kartik\grid\GridView::widget([
    'dataProvider'  =>  new \yii\data\ActiveDataProvider([
        'query' =>  \backend\modules\payments\models\DailyReport::find()
    ]),
    'beforeHeader'  =>  [
        [
            'columns'   =>  [
                [
                ],
                [
                ],
                [
                    'content'   =>  'Сумма продаж из магазина',
                    'options'   =>  [
                        'colspan'   =>  4
                    ]
                ],
                [
                ],
                [
                    'content'   =>  'Сумма заказов на самовывоз',
                    'options'   =>  [
                        'colspan'   =>  4
                    ]
                ],
            ],
        ]
    ],
    'resizableColumns'  =>  false,
    'columns'   =>  [
        [
            'label'     =>  'Дата',
            'attribute' =>  'added',
            'value'     =>  function($model){
                return \Yii::$app->formatter->asDate($model->added, 'php:d.m.Y');
            }
        ],
        [
            'label' =>  \yii\bootstrap\Html::tag('span', 'Продажи<br>из<br>магазина', ['style' => 'text-align: middle']),
            'encodeLabel'   =>  false,
            'attribute' =>  'shopSells',
            'value'     =>  function($model){
                return sizeof($model->shopSells);
            }
        ],
        [
            'label'         =>  'Подтверждено',
            'attribute'     =>  'shopAccepted',
            'value'         =>  function($model){
                return $model->shopAccepted.' грн.';
            }
            //'attribute' =>  'ID'
        ],
        [
            'label' =>  'Подтверждено<br>вами',
            'encodeLabel'   =>  false,
            'attribute'     =>  'shopUserAccepted',
            'value'         =>  function($model){
                return $model->shopUserAccepted.' грн.';
            }
            //'attribute' =>  'ID'
        ],
        [
            'label' =>  'Неподтверждено',
            'attribute'     =>  'shopNotAccepted',
            'value'         =>  function($model){
                return $model->shopNotAccepted.' грн.';
            }
            //'attribute' =>  'ID'
        ],
        [
            'label' =>  'Всего',
            'value' =>  function($model){
                return ($model->shopAccepted + $model->shopNotAccepted).' грн.';
            }
        ],
        [
            'label' =>  'Заказы<br>на<br>самовывоз',
            'encodeLabel'   =>  false,
            'attribute' =>  'selfDelivered',
            'value'     =>  function($model){
                return sizeof($model->selfDelivered);
            }
        ],
        [
            'label'         =>  'Подтверждено',
            'attribute'     =>  'selfDeliveredAccepted',
            'value'         =>  function($model){
                return $model->selfDeliveredAccepted.' грн.';
            }
            //'attribute' =>  'ID'
        ],
        [
            'label' =>  'Подтверждено<br>вами',
            'encodeLabel'   =>  false,
            'attribute'     =>  'selfDeliveredUserAccepted',
            'value'         =>  function($model){
                return $model->selfDeliveredUserAccepted.' грн.';
            }
            //'attribute' =>  'ID'
        ],
        [
            'label' =>  'Неподтверждено',
            'attribute'     =>  'selfDeliveredNotAccepted',
            'value'         =>  function($model){
                return $model->selfDeliveredNotAccepted.' грн.';
            }
            //'attribute' =>  'ID'
        ],
        [
            'label' =>  'Всего',
            'value' =>  function($model){
                return ($model->selfDeliveredAccepted + $model->selfDeliveredNotAccepted).' грн.';
            }
        ],
        [
            'label' =>  'Общая<br>сумма',
            'encodeLabel'   =>  false,
            'value' =>  function($model){
                return ($model->selfDeliveredAccepted + $model->selfDeliveredNotAccepted + $model->shopAccepted + $model->shopNotAccepted).' грн.';
            }
            //'attribute' =>  'ID'
        ],
        [
            'label' =>  'Сумма<br>подтверждённых',
            'encodeLabel'   =>  false,
            'value' =>  function($model){
                return ($model->selfDeliveredAccepted + $model->shopAccepted).' грн.';
            }
            //'attribute' =>  'ID'
        ],
        [
            'label' =>  'Сумма<br>размена',
            'encodeLabel'   =>  false,
            //'attribute' =>  'ID'
        ],
        [
            'label' =>  'Печать',
            //'attribute' =>  'ID'
        ],
    ]
]);