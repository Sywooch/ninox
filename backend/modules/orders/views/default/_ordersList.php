<?php
/**
 * @property $orders ActiveDataProvider
 * @property $model /backend/models/History
 */
use kartik\grid\GridView;
use rmrevin\yii\fontawesome\FA;
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
        switch($model->status){
            case $model::STATUS_PROCESS:
            case $model::STATUS_WAIT_DELIVERY:
                $class = 'warning';
                break;
            case $model::STATUS_NOT_PAYED:
            case $model::STATUS_DELIVERED:
            case $model::STATUS_DONE:
                $class = 'success';
                break;
            case $model::STATUS_NOT_CALLED:
            default:
                $class = 'danger';
                break;
        }

        return ['class' => $class];
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

                if($model->status == $model::STATUS_DONE){
                    $status2 = 'Выполнено '.\Yii::$app->formatter->asDatetime($model->doneDate, 'php:d.m.Y');
                }else{
                    $status2 = 'Не выполнено';
                }

                return Html::tag('div', Html::tag('div', $model->statusDescription, [
                        'style' =>  'width: 100%; height: 40%',
                        'class' =>  'mainStatus'
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
                return  Html::tag('span' , $model->actualAmount.' грн.', ['class' => 'actualAmount']).
                Html::tag('br').
                (!empty($model->responsibleUser) ? Html::tag('small', $model->responsibleUser->name, ['class' => 'responsibleUser']) : '');
            }
        ],
        [
            'header'    =>  'СМС',
            'class'     =>  \kartik\grid\ActionColumn::className(),
            'hAlign'    =>  GridView::ALIGN_CENTER,
            'width'     =>  '90px',
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'buttons'   =>  [
                'sms'   =>  function($url, $model, $key){
                    return Html::button(FA::i('envelope-o'), ['class' => 'btn btn-sm btn-default sms-order']);
                },
                'card' =>  function($url, $model, $key){
                    if(!$model->payOnCard){
                        return '';
                    }

                    return Html::button(FA::i('credit-card'), ['class' => 'btn btn-default sms-card']);
                },
            ],
            'template'  =>  Html::tag('div', '{sms}{card}', ['class' => 'btn-group btn-group-sm sms-buttons'])
        ],
        [
            'class'     =>  \kartik\grid\ActionColumn::className(),
            'hAlign'    =>  GridView::ALIGN_CENTER,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'width'     =>  '250px',
            'header'    =>  '',
            'buttons'   =>  [
                'contents'  =>  function($url, $model, $key){
                    return Html::a('Содержимое', Url::toRoute([
                        '/orders/showorder/'.$model->id
                    ]), [
                        'class'     =>  'btn btn-default',
                        'style'     =>  'margin-top: 1px',
                        'data-pjax' =>  0,
                        'title'     =>  'Содержимое заказа'
                    ]);
                },
                'print'  =>  function($url, $model, $key){
                    return Html::a(FA::i('print'), Url::toRoute([
                        '/printer/invoice/'.$model->id
                    ]), [
                        'target'    =>  '_blank',
                        'class'     =>  'btn btn-default',
                        'data-pjax' =>  0,
                        'title'     =>  'Печатать накладную'
                    ]);
                },
                'done'  =>  function($url, $model, $key){
                    return Html::button(FA::i('check')->size(FA::SIZE_4X), [
                        'class' =>  'btn btn-default doneOrder '.($model->done == 1 ? ' btn-success' : ''),
                        ($model->callback == $model::CALLBACK_COMPLETED ? '' : 'disabled')  =>  'disabled',
                        'title' =>  $model->done == 1 ? 'Заказ выполнен' : 'Заказ не выполнен'
                    ]);
                },
                'call'  =>  function($url, $model, $key){
                    switch($model->callback){
                        case '2':
                            $subclass = 'btn-danger';
                            $title = 'Клиент не отвечает';
                            break;
                        case '1':
                            $subclass = 'btn-success';
                            $title = 'Звонили';
                            break;
                        default:
                            $subclass = 'btn-default';
                            $title = 'Не звонили';
                    }

                    if($model->callback == '0'){
                        $subclass = 'btn-warning';
                    }

                    return Html::button(FA::i('phone'), [
                        'class' =>  'btn btn-default confirmCall '.$subclass,
                        'title' =>  $title
                    ]);
                },
                'changes'   =>  function($url, $model, $key){
                    return Html::a(FA::i('list-alt'), '#orderChanges', [
                        'class'                     =>  'ordersChanges btn btn-default',
                        'data-attribute-orderID'    =>  $model->id,
                        ($model->hasChanges != 1 ? 'disabled' : 'enabled') => 'disabled',
                        'onclick'   =>  ($model->hasChanges != 1 ? 'return false;' : ''),
                        'title' =>  $model->hasChanges == 1 ? 'Изменения по заказу' : 'Заказ ещё не редактировался'
                    ]);
                },
            ],
            'template'  =>  Html::tag('div', Html::tag('div', Html::tag('div', '{contents}', [
                        'class' =>  'btn-group btn-group-sm',
                ]).Html::tag('div', '{print}{changes}{call}',[
                        'class' =>  'btn-group btn-group-sm',
                        'style' =>  'margin-top: 8px;'
                ]), [
                    'class' => 'col-xs-8',
                    'style' =>  'padding-left: 0px'
                ]).Html::tag('div', '{done}', ['class' => 'col-xs-4', 'style' => 'margin-left: -17px; padding-left: 0']), ['class' => 'row'])
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