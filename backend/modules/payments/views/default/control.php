<?php
use kartik\grid\GridView;

$this->title = 'Контроль оплат из магазина и самовывоз';

$this->params['breadcrumbs'][] = [
    'label' =>  'Оплаты',
    'url'   =>  '/payments'
];

$this->params['breadcrumbs'][] = $this->title;

echo \yii\bootstrap\Html::tag('h2', $this->title);

echo "</div>", \yii\bootstrap\Html::tag('div',
    \kartik\grid\GridView::widget([
        'dataProvider'  =>  new \yii\data\ActiveDataProvider([
            'query' =>  \backend\modules\payments\models\DailyReport::find()
        ]),
        'summary'   =>  false,
        'striped'  =>  false,
        'responsive'  =>  false,
        'options'   =>  [
            'class' =>  'col-lg-10 col-lg-offset-1 col-md-12'
        ],
        'condensed'  =>  true,
        'beforeHeader'  =>  [
            [
                'columns'   =>  [
                    [
                        'options'   =>  [
                            'colspan'   =>  2
                        ]
                    ],
                    [
                        'content'   =>  'Сумма продаж из магазина',
                        'options'   =>  [
                            'colspan'   =>  4,
                            'style'     =>  'text-align: center'
                        ]
                    ],
                    [
                    ],
                    [
                        'content'   =>  'Сумма заказов на самовывоз',
                        'options'   =>  [
                            'colspan'   =>  4,
                            'style'     =>  'text-align: center'
                        ]
                    ],
                    [
                        'options'   =>  [
                            'colspan'   =>  4
                        ]
                    ],
                ],
            ]
        ],
        'rowOptions'        =>  function($model){
            if($model->selfDeliveredAccepted + $model->selfDeliveredNotAccepted + $model->shopAccepted + $model->shopNotAccepted == $model->selfDeliveredAccepted + $model->shopAccepted){
                $class = 'success';
            }elseif($model->selfDeliveredAccepted + $model->shopAccepted == 0){
                $class = 'danger';
            }else{
                $class = 'warning';
            }

            return [
                'class' => $class
            ];
        },
        'resizableColumns'  =>  false,
        'columns'   =>  [
            [
                'label'     =>  'Дата',
                'hAlign'    =>  GridView::ALIGN_CENTER,
                'vAlign'    =>  GridView::ALIGN_MIDDLE,
                'attribute' =>  'date',
                'value'     =>  function($model){
                    return \Yii::$app->formatter->asDate($model->date, 'php:d.m.Y');
                }
            ],
            [
                'label' =>  \yii\bootstrap\Html::tag('span', 'Продажи<br>из<br>магазина', ['style' => 'text-align: middle']),
                'encodeLabel'   =>  false,
                'hAlign'    =>  GridView::ALIGN_CENTER,
                'vAlign'    =>  GridView::ALIGN_MIDDLE,
                'attribute' =>  'shopSells',
                'format'    =>  'html',
                'value'     =>  function($model){
                    $sells = sizeof($model->shopSells);

                    if(!empty($sells)){
                        return \yii\bootstrap\Html::a($sells, ['/payments/control/'.\Yii::$app->formatter->asDate($model->date, 'php:Y-m-d'), 'type' => 'shop']);
                    }

                    return $sells;
                }
            ],
            [
                'label'         =>  'Подтверждено',
                'attribute'     =>  'shopAccepted',
                'hAlign'    =>  GridView::ALIGN_CENTER,
                'vAlign'    =>  GridView::ALIGN_MIDDLE,
                'value'         =>  function($model){
                    return $model->shopAccepted.' грн.';
                }
                //'attribute' =>  'ID'
            ],
            [
                'label' =>  'Подтверждено<br>вами',
                'encodeLabel'   =>  false,
                'attribute'     =>  'shopUserAccepted',
                'hAlign'    =>  GridView::ALIGN_CENTER,
                'vAlign'    =>  GridView::ALIGN_MIDDLE,
                'value'         =>  function($model){
                    return $model->shopUserAccepted.' грн.';
                }
                //'attribute' =>  'ID'
            ],
            [
                'label' =>  'Неподтверждено',
                'attribute'     =>  'shopNotAccepted',
                'hAlign'    =>  GridView::ALIGN_CENTER,
                'vAlign'    =>  GridView::ALIGN_MIDDLE,
                'value'         =>  function($model){
                    return $model->shopNotAccepted.' грн.';
                }
                //'attribute' =>  'ID'
            ],
            [
                'label' =>  'Всего',
                'hAlign'    =>  GridView::ALIGN_CENTER,
                'vAlign'    =>  GridView::ALIGN_MIDDLE,
                'value' =>  function($model){
                    return ($model->shopAccepted + $model->shopNotAccepted).' грн.';
                }
            ],
            [
                'label' =>  'Заказы<br>на<br>самовывоз',
                'hAlign'    =>  GridView::ALIGN_CENTER,
                'vAlign'    =>  GridView::ALIGN_MIDDLE,
                'encodeLabel'   =>  false,
                'attribute' =>  'selfDelivered',
                'format'    =>  'html',
                'value'     =>  function($model){
                    $sells = sizeof($model->selfDelivered);

                    if(!empty($sells)){
                        return \yii\bootstrap\Html::a($sells, ['/payments/control/'.\Yii::$app->formatter->asDate($model->date, 'php:Y-m-d'), 'type' => 'selfDelivered']);
                    }

                    return $sells;
                }
            ],
            [
                'label'         =>  'Подтверждено',
                'hAlign'    =>  GridView::ALIGN_CENTER,
                'vAlign'    =>  GridView::ALIGN_MIDDLE,
                'attribute'     =>  'selfDeliveredAccepted',
                'value'         =>  function($model){
                    return $model->selfDeliveredAccepted.' грн.';
                }
                //'attribute' =>  'ID'
            ],
            [
                'label' =>  'Подтверждено<br>вами',
                'hAlign'    =>  GridView::ALIGN_CENTER,
                'vAlign'    =>  GridView::ALIGN_MIDDLE,
                'encodeLabel'   =>  false,
                'attribute'     =>  'selfDeliveredUserAccepted',
                'value'         =>  function($model){
                    return $model->selfDeliveredUserAccepted.' грн.';
                }
                //'attribute' =>  'ID'
            ],
            [
                'label' =>  'Неподтверждено',
                'hAlign'    =>  GridView::ALIGN_CENTER,
                'vAlign'    =>  GridView::ALIGN_MIDDLE,
                'attribute'     =>  'selfDeliveredNotAccepted',
                'value'         =>  function($model){
                    return $model->selfDeliveredNotAccepted.' грн.';
                }
                //'attribute' =>  'ID'
            ],
            [
                'label' =>  'Всего',
                'hAlign'    =>  GridView::ALIGN_CENTER,
                'vAlign'    =>  GridView::ALIGN_MIDDLE,
                'value' =>  function($model){
                    return ($model->selfDeliveredAccepted + $model->selfDeliveredNotAccepted).' грн.';
                }
            ],
            [
                'label' =>  'Общая<br>сумма',
                'hAlign'    =>  GridView::ALIGN_CENTER,
                'vAlign'    =>  GridView::ALIGN_MIDDLE,
                'encodeLabel'   =>  false,
                'value' =>  function($model){
                    return ($model->selfDeliveredAccepted + $model->selfDeliveredNotAccepted + $model->shopAccepted + $model->shopNotAccepted).' грн.';
                }
                //'attribute' =>  'ID'
            ],
            [
                'label' =>  'Сумма<br>подтверждённых',
                'hAlign'    =>  GridView::ALIGN_CENTER,
                'vAlign'    =>  GridView::ALIGN_MIDDLE,
                'encodeLabel'   =>  false,
                'value' =>  function($model){
                    return ($model->selfDeliveredAccepted + $model->shopAccepted).' грн.';
                }
                //'attribute' =>  'ID'
            ],
            [
                'class'     =>  \kartik\grid\ActionColumn::className(),
                'header'     =>  'Размен',
                'buttons'   =>  [
                    'editable'  =>  function($key, $model){
                        return \kartik\editable\Editable::widget([
                            'model'         =>  $model->moneyExchange,
                            'attribute'     =>  'summ',
                            'inputFieldConfig'  =>  [
                                'options'   =>  [
                                    'id'            =>  'moneyExchange-'.strtotime($model->date),
                                ]
                            ],
                            'options'   =>  [
                                'id'            =>  'moneyExchange-'.strtotime($model->date),
                            ],
                            'valueIfNull'   =>  0,
                            'ajaxSettings'       =>  [
                                'url'   =>  \yii\helpers\Url::to(['/payments/default/update-exchange?date='.$model->date]),
                            ]
                        ]);
                    }
                ],
                'template'  =>  '{editable}'
            ],
            /*[
                'class'     =>  \kartik\grid\EditableColumn::className(),
                'editableOptions'   =>  [
                    'editableKey'   =>  'date',
                    'ajaxSettings'  =>  [
                        'url'   =>  \yii\helpers\Url::to(['/orders/editable-edit'])
                    ]
                ],
                'hAlign'    =>  GridView::ALIGN_CENTER,
                'vAlign'    =>  GridView::ALIGN_MIDDLE,
                'width'     =>  '150px',
                'label'     =>  'Сумма<br>размена',
                'attribute' =>  'moneyExchange',
                'encodeLabel'   =>  false,
                'value'     =>  function($model){
                    return $model->actualAmount.' грн.';
                }
            ],*/
            [
                'hAlign'    =>  GridView::ALIGN_CENTER,
                'vAlign'    =>  GridView::ALIGN_MIDDLE,
                'label' =>  'Печать',
                //'attribute' =>  'ID'
            ],
        ]
    ]),
    [
        'class' =>  'container-fluid'
    ]), '<div class="container">';