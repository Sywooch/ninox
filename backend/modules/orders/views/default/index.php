<?php
use backend\modules\orders\widgets\OrdersSearchWidget;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
use yii\helpers\Url;

$js = <<<'JS'
$("a.deleteOrder").on('click', function(e){
    deleteOrder(e.currentTarget);
});

$("a.ordersChanges").on('click', function(e){
    ordersChanges(e.currentTarget);
});

$("a.restoreOrder").on('click', function(e){
    restoreOrder(e.currentTarget);
});

$("button.doneOrder").on('click', function(e){
    doneOrder(e.currentTarget);
});

$("button.confirmCall").on('click', function(e){
    confirmCall(e.currentTarget);
});

var ordersChanges = function(e){
    $.ajax({
        type: 'POST',
        url: '/orders/orderchanges',
        data: {
            'OrderID': e.getAttribute('data-attribute-orderid')
        },
        success: function(data){
            console.log(e.getAttribute('data-attribute-orderid'));
            document.querySelector('div[data-remodal-id="orderChanges"]').innerHTML = data;
        }
    });
}, restoreOrder = function(item){
    console.log(item);
    alert('end me plz! file: index.php');
}, deleteOrder = function(item){
    var container   = item.parentNode.parentNode.parentNode,
        orderID     = container.getAttribute('data-key');

    swal({
        title:  'Подождите, пожалуйста...',
        text:   'Возвращаем товары на склад...',
        type: 'info',
        showConfirmButton: false,
        closeOnConfirm: false
    });

    $.ajax({
        type: 'POST',
        url: '/orders/deleteorder',
        data: {
            'OrderID': orderID
        },
        success: function(data){
            swal("Удалён!", "Заказ успешно удалён!", "success");
            container.remove();
            var a = document.querySelector('.kv-expand-detail-row[data-key="' + orderID + '"]');
            if(a !== undefined && a != null){
                a.remove();
            }
        }
    });
}, doneOrder = function(obj){
    var orderID = obj.parentNode.parentNode.parentNode.getAttribute('data-key');
    $.ajax({
        type: 'POST',
        url: '/orders/doneorder',
        data: {
            'OrderID': orderID
        },
        success: function(data){
            if(data == 1){
                obj.setAttribute('class', 'btn-success ' + obj.getAttribute('class'));
            }else{
                obj.setAttribute('class', obj.getAttribute('class').replace(/btn-success/g));
            }
        }
    });
}, confirmCall = function(obj){
    var orderID = obj.parentNode.parentNode.parentNode.getAttribute('data-key');

    swal({
        title: "Вы дозвонились?",
        text: "Вы дозвонились этому клиенту?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#87D37C",

        confirmButtonText: "Дозвонились",
        cancelButtonText: "Не дозвонились",
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function(isConfirm){
        $.ajax({
            type: 'POST',
            url: '/orders/confirmordercall',
            data: {
                'OrderID': orderID,
                'confirm': isConfirm
            },
            success: function(data){
                //TODO: can refactor
                obj.setAttribute('class', obj.getAttribute('class').replace(/btn-\w+/g));
                if(data == 1){
                    obj.setAttribute('class', 'btn-success ' + obj.getAttribute('class'));
                    if(obj.parentNode.querySelector("button[disabled]") !== null){
                        obj.parentNode.querySelector("button[disabled]").removeAttribute('disabled');
                    }
                }else{
                    obj.setAttribute('class', 'btn-danger ' + obj.getAttribute('class'));
                    if(obj.parentNode.querySelector("button.doneOrder") !== null){
                        obj.parentNode.querySelector("button.doneOrder").setAttribute('disabled', 'disabled');
                    }
                }
            }
        });

        swal.close();
    });
}
JS;

$css = <<<'CSS'
.kv-expand-detail-row, .kv-expand-detail-row:hover{
    background: #fff !important;
    border: 3px solid #000;
    border-top: none;
    border-collapse: collapse;
}

.kv-expand-detail-row table td{
    vertical-align: middle !important;
    line-height: 100% !important;
}

.orders-statistics{
    display: inline-block;
    font-size: 12px;
}

.orders-statistics li{
    display: inline-block;
    float: left;
    list-style: none;
    margin-left: 15px;
}

.orders-statistics li a{
    color: #444;
}

.orders-statistics li span, .orders-statistics li a:hover span{
    text-decoration: none !important;
}

.tab-content{
    padding: 0;
}

#accordion {
	list-style:none;
	padding:0;
	overflow:hidden;
}
#accordion .panel {
	float:left;
	display:block;
	width: 100px;
	height: 40px;
	overflow:hidden;
	text-decoration:none;
	font-size: 12px;
	line-height: 24px;
	vertical-align: middle;
	text-align: center;
	color: #fff;
	margin-right: 20px;
	margin-bottom: 0;
	border: none;
	background-color: transparent;
	box-shadow: none;
}

#accordion .panel .panelContent{
    display: none;
}

#accordion .panel.active {
	width: 340px;
	margin-right: 0px;
}

#accordion .panel.active .panelContent{
    display: inline-block;
}
.pink, #accordion .panel .header {
	width: 98px;
	padding: 5px 10px;
	border-radius: 5px;
	cursor:pointer;
	background: #e5e5e5;
	color: #000;
	float: left;
}


.pink, #accordion .panel.active .header {
	background: #74b009;
	color: #fff;
}

.last {
	border:none
}

.-accordion--horizontal{
    height: auto;
}
CSS;

$js = <<<'JS'
$(document).ready(function(){

    activePanel = $("#accordion div.panel:first");
    $(activePanel).addClass('active');

    $("#accordion").delegate('.panel', 'click', function(e){
        if( ! $(this).is('.active') ){
			$(activePanel).animate({width: "100px"}, 300);
			$(this).animate({width: "340px"}, 300);
			$('#accordion .panel').removeClass('active');
			$(this).addClass('active');
			activePanel = this;
		 };
    });
});
JS;

$this->registerJs($js);

\bobroid\sweetalert\SweetalertAsset::register($this);

$this->registerJs($js);
$this->registerCss($css);

$this->title = 'Заказы';
?>
<style>
    .ordersStats{
        /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#33363f+73,1c202a+77 */
        background: rgb(51,54,63); /* Old browsers */
        background: -moz-linear-gradient(top,  rgba(51,54,63,1) 73%, rgba(28,32,42,1) 77%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, left bottom, color-stop(73%,rgba(51,54,63,1)), color-stop(77%,rgba(28,32,42,1))); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(top,  rgba(51,54,63,1) 73%,rgba(28,32,42,1) 77%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top,  rgba(51,54,63,1) 73%,rgba(28,32,42,1) 77%); /* Opera 11.10+ */
        background: -ms-linear-gradient(top,  rgba(51,54,63,1) 73%,rgba(28,32,42,1) 77%); /* IE10+ */
        background: linear-gradient(to bottom,  rgba(51,54,63,1) 73%,rgba(28,32,42,1) 77%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#33363f', endColorstr='#1c202a',GradientType=0 ); /* IE6-9 */
        width: 100%;
        height: 90px;
        border-radius: 3px;
        vertical-align: middle;
        font-family: "Open Sans";
    }

    .ordersStats .fa{
        vertical-align: middle;
        padding: 4px;
        border-radius: 3px;
        margin-left: 15px;
        width: 53px;
        height: 53px;
        text-align: center;
        line-height: 45px;

    }

    .ordersStats .fa.yellow{
        background: #ffc76c;
    }

    .ordersStats .fa.purple{
        background: #cb93dd;
    }

    .ordersStats .fa.green{
        background: #64decf;
    }

    .ordersStats .fa.blue{
        background: #84c0eb;
    }

    .ordersStats > div > div{
        height: 88px;
        background: #fff;
        margin: 0 30px;
        border-bottom: 3px solid #cfcfcf;
        width: 220px;
    }

    .ordersStats div > div{
        line-height: 85px;
        float: left;
    }

    .ordersStats .description{
        margin-left: 10px;
        line-height: 20px;
        height: 56px;
        margin-top: 13px;
    }

    .ordersStats .description span{
        line-height: 11px;
        font-size: 11px;
    }

    .ordersStats .description h1{
        font-size: 28px;
        line-height: 40px;
        padding: 0;
        margin: 0;
    }

    .ordersStats .description table td{
        padding-right: 10px;
    }

    .ordersStats .description table td:last-child{
        padding-left: 10px;
        padding-right: 0;
        border-left: 1px solid black;
    }

    .ordersStats .icon{
        float: left;
    }
</style>
    <div class="ordersStats">
        <div style="display: table; margin: 0 auto; position: relative; top: 11px;">
            <div style="display: table-cell;">
                <div>
                    <div class="icon">
                        <?=FA::icon('dropbox', [
                            'class' =>  'yellow'
                        ])->size(FA::SIZE_3X)->inverse()?>
                    </div>
                    <div class="description">
                        <table>
                            <tr>
                                <td>
                                    <span>Заказов</span>
                                </td>
                                <td>
                                    <span>Выполнено</span>
                                </td>
                            </tr>
                            <tr style="text-align: center;">
                                <td>
                                    <?=Html::tag('h1', strlen($ordersStats['totalOrders']) >= 4 ? Html::tag('small', $ordersStats['totalOrders']) : $ordersStats['totalOrders'], [
                                        'style' =>  'line-height: '.(strlen($ordersStats['totalOrders']) < 4 ? '26px;' : '0px;')
                                    ])?>
                                </td>
                                <td>
                                    <?=Html::tag('h1', strlen($ordersStats['completedOrders']) >= 4 ? Html::tag('small', $ordersStats['completedOrders'], ['style' => 'color: #fff']) : $ordersStats['completedOrders'], [
                                        'style' =>  'color: #fff; min-width: 36px; background: #B5B5B5; padding: 5px; border-radius: 3px; display: inline-block; line-height: '.(strlen($ordersStats['completedOrders']) < 4 ? '26px;' : '0px;')
                                    ])?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div style="display: table-cell;">
                <div>
                    <div class="icon">
                        <?=FA::icon('frown-o', [
                            'class' =>  'purple'
                        ])->size(FA::SIZE_3X)->inverse()?>
                    </div>
                    <div class="description">
                        <span>Не прозвонено</span>
                        <h1><?=$ordersStats['notCalled']?></h1>
                    </div>
                </div>
            </div>
            <div style="display: table-cell;">
                <div>
                    <div class="icon">
                        <?=FA::icon('cubes', [
                            'class' =>  'green'
                        ])->size(FA::SIZE_2X)->inverse()?>
                    </div>
                    <div class="description">
                        <span>Всего на складе</span>
                        <h1>150 000</h1>
                    </div>
                </div>
            </div>
            <div style="display: table-cell;">
                <div>
                    <div class="icon">
                        <?=FA::icon('calculator', [
                            'class' =>  'blue'
                        ])->size(FA::SIZE_3X)->inverse()?>
                    </div>
                    <div class="description">
                        <span>Сума заказов</span>
                        <h1 title="Фактическая сумма заказов" style="line-height: 22px; font-size: 22px;"><?=$ordersStats['ordersFaktSumm']?>₴</h1>
                        <small title="Общая сумма заказов"><?=$ordersStats['ordersSumm']?>₴</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?=\backend\widgets\CollectorsWidget::widget([
    'showUnfinished'    =>  $showUnfinished,
    'items'             =>  $collectors
])?>

<div class="row well well-sm" style="margin: 30px 0;">
    <?=OrdersSearchWidget::widget([
        'searchModel'   =>  $searchModel,
        'items'         =>  [
            [
                'label'     =>  '№ заказа',
                'attribute' =>  'number'
            ],[
                'label'     =>  'Телефон',
                'attribute' =>  'customerPhone'
            ],[
                'label'     =>  'Фамилия',
                'attribute' =>  'customerSurname'
            ],[
                'label'     =>  'Эл. адрес',
                'attribute' =>  'customerEmail'
            ],[
                'label'     =>  'ТТН',
                'attribute' =>  'nakladna'
            ],[
                'label'     =>  'Сумма',
                'attribute' =>  'actualAmount'
            ],
        ]
    ])?>
</div>

<?=Html::tag('a', 'За всё время', [
    'href'  =>  \yii\helpers\Url::toRoute([
        '',
        'showDates' =>  'alltime'
    ]),
    'class' =>  'btn btn-default btn-disabled',
    \Yii::$app->request->get("showDates") == 'alltime' ? 'disabled' : '' =>  'true'
]); ?>

<?=\kartik\tabs\TabsX::widget([
    'encodeLabels'  =>  false,
    'pluginEvents'  =>  [
        'tabsX.success' =>  'function(){
            var setListeners = function(selector){
                $(document).pjax(selector + " a", selector + "-pjax", {"push":false,"replace":false,"timeout":10000,"scrollTo":true});

                $(selector + "-pjax").on(\'pjax:timeout\', function(e){
                    e.preventDefault()
                }).on(\'pjax:send\', function(){
                    $(selector + "-container").addClass(\'kv-grid-loading\')
                }).off(\'pjax:complete\').on(\'pjax:complete\', function(){
                    $(selector + "-container").removeClass(\'kv-grid-loading\');
                });
            }

            if($("#ordersGridView_-pjax").length > 0){
                setListeners("#ordersGridView_");
            }

            if($("#ordersGridView_market-pjax").length > 0){
                setListeners("#ordersGridView_market");
            }

            if($("#ordersGridView_all-pjax").length > 0){
                setListeners("#ordersGridView_all");
            }
         }'
    ],
    'enableStickyTabs'  =>  false,
    'items' =>  [
        [
            'label'   =>  'Интернет',
            'options'   =>  [
                'id'        =>  'source-internet',
            ],
            'content'   =>  \yii\helpers\Json::decode($this->context->runAction('showlist', ['context' => true])),
            'active'    =>  true,
            'linkOptions'   =>  ['data-url' =>  Url::to(['/orders/showlist', 'showDates' => \Yii::$app->request->get('showDates')])]
        ],
        [
            'label'   =>  'Магазин',
            'options'   =>  [
                'id'        =>  'source-local_store',
                'linkOptions'   =>  ['data-url' =>  Url::to(['/orders/showlist', 'showDates' => \Yii::$app->request->get('showDates'), 'ordersSource' => 'market'])]
            ],
        ],
        [
            'label'   =>  'Все',
            'options'   =>  [
                'id'        =>  'source-all',
            ],
            'linkOptions'   =>  ['data-url' =>  Url::to(['/orders/showlist', 'showDates' => \Yii::$app->request->get('showDates'), 'ordersSource' => 'all'])]
        ]
    ]
])?>
<?='';/*\kartik\grid\GridView::widget([
    'dataProvider'  =>  $orders,
    'resizableColumns' =>  false,
    'summary'   =>  '',
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
    'beforeHeader'  =>  '<div style="margin-bottom: -1px;" class="btn-group">
<a href="'.RequestHelper::createGetLink('ordersSource', '').'" class="btn btn-default"'.(\Yii::$app->request->get("ordersSource") == 'shop' || \Yii::$app->request->get("ordersSource") == '' ? ' disabled' : '').'>Интернет</a>
<a href="'.RequestHelper::createGetLink('ordersSource', 'market').'" class="btn btn-default"'.(\Yii::$app->request->get("ordersSource") == 'market' ? ' disabled' : '').'>Магазин</a>
<a href="'.RequestHelper::createGetLink('ordersSource', 'all').'" class="btn btn-default"'.(\Yii::$app->request->get("ordersSource") == 'all' ? ' disabled' : '').'>Все</a>
</div>',
    'hover'         =>  true,
    'columns'       =>  [
        [
            'attribute' =>  'id',
            'format'    =>  'html',
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
            'attribute' =>  'name',
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
            'hAlign'    =>  GridView::ALIGN_CENTER,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'width'     =>  '80px',
        ],
        [
            'attribute' =>  'deliveryCity',
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
            'header'    =>  'Сумма заказа',
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
            'buttons'   =>  [
                'contents'  =>  function($url, $model, $key){
                    return Html::a('Содержимое', Url::toRoute([
                        '/orders/showorder/'.$model->id
                    ]), [
                        'class' =>  'btn btn-default',
                        'style' =>  'margin-top: 1px'
                    ]);
                },
                'print'  =>  function($url, $model, $key){
                    return Html::a('', Url::toRoute([
                        '/printer/order/'.$model->id
                    ]), [
                        'target'    =>  '_blank',
                        'class'     =>  'btn btn-default glyphicon glyphicon-print'
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
            'onDetailLoaded'    =>  'function(){
                //TODO: вешать на кнопки eventListener\'ы
            }'
        ],
    ]
]);*/

$modal = new \bobroid\remodal\Remodal([
    'id'            =>  'orderChanges',
    'cancelButton'  =>  false,
    'confirmButton' =>  false,
    'addRandomToID' =>  false,
    'events'    =>  [
        'opening'  =>   new \yii\web\JsExpression("
            console.log(e);
        ")
    ]
]);
echo $modal->renderModal();
?>