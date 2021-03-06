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
                $class = 'warning';
                break;
            case $model::STATUS_DELIVERED:
            case $model::STATUS_NOT_PAYED:
            case $model::STATUS_WAIT_DELIVERY:
            case $model::STATUS_DONE:
                $class = 'success';
                break;
            case $model::STATUS_NOT_CALLED:
                $class = 'notCalled';
                break;
            default:
                $class = 'new';
                break;
        }

        if($model->sourceType == $model::SOURCETYPE_INTERNET && $model->sourceInfo == 1 && $model->status == $model::STATUS_NOT_CALLED){
            $class = 'danger';
        }

        if($model->newCustomer){
            $class .= ' newCustomer';
        }

        if(!empty($model->sumDiscount)){
            $class .= ' hasDiscount';
        }

        return ['class' => 'orderRow '.$class];
    },
    'hover'         =>  true,
    'columns'       =>  [
        [
            'attribute' =>  'id',
            'format'    =>  'html',
            'label'     =>  '№',
            'hAlign'    =>  GridView::ALIGN_LEFT,
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
            'hAlign'    =>  GridView::ALIGN_LEFT,
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
            'hAlign'    =>  GridView::ALIGN_LEFT,
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
            'hAlign'    =>  GridView::ALIGN_LEFT,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'width'     =>  '80px',
            'value'     =>  function($model){
                return \Yii::$app->formatter->asPhone($model->customerPhone);
            }
        ],
        [
            'attribute' =>  'deliveryCity',
            'label'     =>  'Город/Область',
            'format'    =>  'html',
            'width'     =>  '140px',
            'hAlign'    =>  GridView::ALIGN_LEFT,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'value'     =>  function($model){
                if(strlen($model->deliveryCity) >= 20){
                    $a = explode(',', $model->deliveryCity);
                    $model->deliveryCity = implode(', ', $a);
                }

                return Html::tag('b', $model->deliveryCity).'<br>'.Html::tag('small', $model->deliveryRegion);
            }
        ],
        [
            'header'    =>  'Статус',
            'vAlign'    =>  \kartik\grid\GridView::ALIGN_MIDDLE,
            'hAlign'    =>  \kartik\grid\GridView::ALIGN_LEFT,
            'format'    =>  'html',
            'noWrap'    =>  true,
            'attribute' =>  'status',
            'value'     =>  function($model){

                if($model->status == $model::STATUS_DONE || $model->status == $model::STATUS_NOT_PAYED || ($model->status == $model::STATUS_WAIT_DELIVERY && $model->paymentType == 1)){
                    $status2 = 'Выполнено '.\Yii::$app->formatter->asDate($model->doneDate);
                }elseif($model->status == $model::STATUS_WAIT_DELIVERY && $model->paymentType == 2){
                    $status2 = 'Оплачено '.\Yii::$app->formatter->asDate($model->moneyConfirmedDate);
                }else{
                    $status2 = '';
                }

                $d = $model->statusDescription;

                if($model->status == $model::STATUS_NOT_PAYED || $model->status == $model::STATUS_WAIT_DELIVERY){
                    $d = Html::tag('b', $model->statusDescription);
                }

                return Html::tag('div', Html::tag('div', $d, [
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
                $class = '';
                $value = '';
                switch($model->paymentType){
                    case 1:
                        $value = 'наложка';
                        $class = ' cash-on-delivery';
                        break;
                    case 2:
                        $value = 'на карту';
                        $class = ' cash-on-card';
                        break;
                    case 3:
                        $value = 'наличка';
                        $class = ' cash';
                        break;
                    default:
                        break;
                }

                $priceDiff = 0;

                if($model->actualAmount != 0){
                    $priceDiff = round($model->actualAmount - $model->originalSum, 2);
                }

                return Html::tag('div', $model->originalSum.' грн.').
                ($priceDiff > 1 || $priceDiff < -1 ? Html::tag('div', Html::tag('small', "$priceDiff грн.", ['class' => 'text-'.($priceDiff > 0 ? 'success' : 'danger')])) : '').
                    Html::tag('span', $value, ['class' => 'payment-type'.$class]);
            }
        ],
        [
            'hAlign'    =>  GridView::ALIGN_LEFT,
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
                Html::tag('small', 'Товаров '.count($model->items), ['style' => 'display: block']).
                Html::tag('br').
                (!empty($model->responsibleUser) ? Html::tag('small', $model->responsibleUser->name, ['class' => 'responsibleUser']) : '');
            }
        ],
        [
            'header'    =>  'СМС',
            'class'     =>  \kartik\grid\ActionColumn::className(),
            'hAlign'    =>  GridView::ALIGN_LEFT,
            'width'     =>  '90px',
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'buttons'   =>  [
                'sms'   =>  function($url, $model, $key){
                    return Html::button(FA::i('envelope-o'), ['class' => 'btn btn-sm btn-default sms-order'.((empty($model->nakladnaSendDate) || $model->nakladnaSendDate == '0000-00-00 00:00:00') ? '' : ' success')]);
                },
                'card' =>  function($url, $model, $key){
                    if(!$model->payOnCard){
                        return '';
                    }

                    return Html::button(FA::i('credit-card'), ['class' => 'btn btn-default sms-card'.((empty($model->smsSendDate) || $model->smsSendDate == '0000-00-00 00:00:00') ? '' : ' success')]);
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
                        case '0':
                            $subclass = 'btn-warning';
                            $title = 'Не звонили';
                            break;
                        case '1':
                            $subclass = 'btn-success';
                            $title = 'Звонили'.($model->callbackDate != '0000-00-00 00:00:00' ? ' '.$model->callbackDate : '');
                            break;
                        default:
                            $subclass = 'btn-danger';
                            $title = \Yii::t('backend', "Клиент не отвечает.\r\nЗвонили {n, plural, one{# раз} few{# раза} many{# раз} other{# раз}}.\r\nПоследний раз {date}.",
                                [
                                    'n' => $model->callback - 1,
                                    'date'  =>  $model->callbackDate,
                                ]
                            );
                    }

                    return Html::button(FA::i('phone'), [
                        'class' =>  'btn confirmCall '.$subclass,
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
            'detailUrl' =>  '/orders/order-preview',
        ],
    ],
    'export'    =>  false
]);