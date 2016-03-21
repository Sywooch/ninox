<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

if($orderSource == false){
    $orderSource = \Yii::$app->request->get('ordersSource');
}

echo \kartik\grid\GridView::widget([
    'id'            =>  'ordersGridView_'.$orderSource,
    'dataProvider'  =>  $orders,
    'resizableColumns' =>  false,
    'pjax'  =>  true,
    'pjaxSettings'   =>  [
        'options' => [
            'enablePushState'       =>  false,
            'enableReplaceState'    =>  false,
            'timeout'               =>  '10000',
            'id'                    =>  'ordersGridView_'.$orderSource.'-pjax'
        ]
    ],
    'summary'   =>  false,
    'options'       =>  [
        'style' =>  'overflow: hidden',
        'data-attribute-type'   =>  'ordersGrid'
    ],
    'rowOptions'    =>  function($model){
        if($model->deleted != 0){
            return ['class' => 'danger'];
        }

        if($model->done == 1){
            return ['class' =>  'success'];
        }

        if($model->confirmed == 1){
            return ['class' =>  'warning'];
        }

        if($model->callback == -1 || $model->callback == 0){
            return ['class' =>  'danger'];
        }

        return [];
    },
    'hover'         =>  true,
    'columns'       =>  [
        [
            'attribute' =>  'id',
            'format'    =>  'html',
            'label'     =>  '№',
            'hAlign'    =>  GridView::ALIGN_CENTER,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'width'     =>  '40px',
            'options'   =>  function($model){
                return [];
            },
            'value'     =>  function($model){
                return Html::a($model->number, Url::to([
                    '/orders/showorder/'.$model->id
                ])).Html::tag('br').Html::tag('small',
                    Html::a($model->deleted != 0 ? Html::tag('small', 'Восст.') : 'Удалить', '#', [
                        'class' =>  $model->deleted != 0 ? 'restoreOrder' : 'deleteOrder'
                    ]));
            }
        ],
        [
            'attribute' =>  'added',
            'label'     =>  'Дата',
            'hAlign'    =>  GridView::ALIGN_CENTER,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'width'     =>  '40px',
            'format'    =>  'html',
            'options'   =>  function($model){
                return [];
            },
            'value'     =>  function($model){
                return \Yii::$app->formatter->asDate($model->added, 'php:d.m').'<br>'.
                \Yii::$app->formatter->asDate($model->added, 'php:H').
                Html::tag('sup', Html::tag('u', \Yii::$app->formatter->asDate($model->added, 'php:i')));
            }
        ],
        [
            'attribute' =>  'customerName',
            'label'     =>  'Ф.И.О.',
            'hAlign'    =>  GridView::ALIGN_CENTER,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'width'     =>  '140px',
            'format'    =>  'html',
            'value'     =>  function($model){
                return $model->customerName.'<br>'.$model->customerSurname;
            }
        ],
        [
            'attribute' =>  'customerPhone',
            'label'     =>  'Телефон',
            'hAlign'    =>  GridView::ALIGN_CENTER,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'width'     =>  '80px',
        ],
        [
            'attribute' =>  'deliveryCity',
            'label'     =>  'Город/Область',
            'format'    =>  'html',
            'width'     =>  '140px',
            'hAlign'    =>  GridView::ALIGN_CENTER,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'value'     =>  function($model){
                if(strlen($model->deliveryCity) >= 20){
                    $a = explode(',', $model->deliveryCity);
                    $model->deliveryCity = implode(', ', $a);
                }

                return Html::tag('b', $model->deliveryCity).'<br>'.$model->deliveryRegion;
            }
        ],
        [
            'header'    =>  'Статус',
            'vAlign'    =>  \kartik\grid\GridView::ALIGN_MIDDLE,
            'hAlign'    =>  \kartik\grid\GridView::ALIGN_CENTER,
            'format'    =>  'html',
            'noWrap'    =>  true,
            'attribute' =>  'status',
            'value'     =>  function($model){
                if(!empty($model->status)){
                    $string = 'status_'.$model->status;
                    $status1 = $model::$$string;
                }else{
                    $status1 = '';
                }

                if($model->status == '1' && $model->done == 1 && $model->doneDate != '0000-00-00 00:00:00'){
                    $status2 = 'Выполнено '.\Yii::$app->formatter->asDatetime($model->doneDate, 'php:d.m.Y');
                }else{
                    $status2 = 'Не выполнено';
                }

                return Html::tag('div', Html::tag('div', $status1, [
                        'style' =>  'width: 100%; height: 40%'
                    ]).Html::tag('small', $status2), [
                    'style' =>  'width: 100%; display: block; position: inherit; height: 100%;'
                ]);
            }
        ],
        [
            'width'     =>  '70px',
            'format'    =>  'html',
            'attribute' =>  'originalSum',
            'header'    =>  'Сумма',
            'noWrap'    =>  true,
            'hAlign'    =>  GridView::ALIGN_CENTER,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'value'     =>  function($model){
                return $model->originalSum.' грн.';
            }
        ],
        [
            'hAlign'    =>  GridView::ALIGN_CENTER,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'attribute' =>  'actualAmount',
            'label'     =>  'К оплате',
            'width'     =>  '70px',
            'format'    =>  'html',
            'options'   =>  [
                'style'      =>  'font-size: 8px'
            ],
            'noWrap'    =>  true,
            'value'     =>  function($model){
                $user = \common\models\Siteuser::getUser($model->responsibleUserID);
                return  Html::tag('span' , $model->actualAmount.' грн.', ['class' => 'actualAmount']).
                Html::tag('br').
                ($model->responsibleUserID != 0 && !empty($model->responsibleUserID) ? Html::tag('small', (is_object($user) ? $user->name : $user), ['class' => 'responsibleUser']) : '');
            }
        ],
        [
            'header'    =>  'СМС',
            'hAlign'    =>  GridView::ALIGN_CENTER,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
        ],
        [
            'class'     =>  \kartik\grid\ActionColumn::className(),
            'hAlign'    =>  GridView::ALIGN_CENTER,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'width'     =>  '180px',
            'header'    =>  '',
            'buttons'   =>  [
                'contents'  =>  function($url, $model, $key){
                    return Html::a('Содержимое', Url::toRoute([
                        '/orders/showorder/'.$model->id
                    ]), [
                        'class'     =>  'btn btn-default',
                        'style'     =>  'margin-top: 1px',
                        'data-pjax' =>  0
                    ]);
                },
                'print'  =>  function($url, $model, $key){
                    return Html::a('', Url::toRoute([
                        '/printer/order/'.$model->id
                    ]), [
                        'target'    =>  '_blank',
                        'class'     =>  'btn btn-default glyphicon glyphicon-print',
                        'data-pjax' =>  0
                    ]);
                },
                'done'  =>  function($url, $model, $key){
                    return Html::button('', [
                        'class' =>  'btn btn-default doneOrder glyphicon glyphicon-ok'.($model->done == 1 ? ' btn-success' : ''),
                        ($model->confirmed == 1 ? '' : 'disabled')  =>  'disabled'
                    ]);
                },
                'call'  =>  function($url, $model, $key){
                    switch($model->callback){
                        case '2':
                            $subclass = 'btn-danger';
                            break;
                        case '1':
                            $subclass = 'btn-success';
                            break;
                        default:
                            $subclass = 'btn-default';
                    }

                    if($model->callback == '0'){
                        $subclass = 'btn-warning';
                    }

                    return Html::button('', [
                        'class' =>  'btn confirmCall glyphicon glyphicon-phone-alt '.$subclass
                    ]);
                },
                'changes'   =>  function($url, $model, $key){
                    return Html::a('', '#orderChanges', [
                        'class'                     =>  'ordersChanges btn btn-default glyphicon glyphicon-list-alt',
                        'data-attribute-orderID'    =>  $model->id,
                        ($model->hasChanges != 1 ? 'disabled' : 'enabled') => 'disabled',
                        'onclick'   =>  ($model->hasChanges != 1 ? 'return false;' : '')
                    ]);
                },
            ],
            'template'  =>  Html::tag('div', '{contents}', [
                    'class' =>  'btn-group btn-group-sm',
                ]).Html::tag('div', '{print}{changes}{call}{done}',[
                    'class' =>  'btn-group btn-group-sm',
                    'style' =>  'margin-top: -2px;'
                ])
        ],
        [
            'class'     =>  \kartik\grid\ExpandRowColumn::className(),
            'value'     =>  function(){
                return GridView::ROW_COLLAPSED;
            },
            'detailRowCssClass' =>  GridView::TYPE_DEFAULT,
            'detailUrl' =>  '/orders/getorderpreview',
        ],
    ],
    'export'    =>  false
]);