<?php
use kartik\grid\GridView;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;

$js = <<<'SCRIPT'
var dOrders = document.querySelectorAll("a.deleteOrder"),
    dButton = document.querySelectorAll("button.doneOrder"),
    cButton = document.querySelectorAll("button.confirmCall");

for(var i = 0; i < dOrders.length; i++){
    dOrders[i].addEventListener('click', function(e){
        deleteOrder(e.currentTarget);
    }, false);
}

for(var i = 0; i < dButton.length; i++){
    dButton[i].addEventListener('click', function(e){
        doneOrder(e.currentTarget);
    }, false);
}

for(var i = 0; i < cButton.length; i++){
    cButton[i].addEventListener('click', function(e){
        confirmCall(e.currentTarget);
    }, false);
}

var deleteOrder = function(item){
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
                if(data == 1){
                    obj.setAttribute('class', obj.getAttribute('class').replace(/btn-\w+/g));
                    obj.setAttribute('class', 'btn-success ' + obj.getAttribute('class'));
                    if(obj.parentNode.querySelector("button[disabled]") !== null){
                        obj.parentNode.querySelector("button[disabled]").removeAttribute('disabled');
                    }
                }else{
                    obj.setAttribute('class', obj.getAttribute('class').replace(/btn-\w+/g));
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
SCRIPT;

$css = <<<'STYLE'
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
STYLE;


\bobroid\sweetalert\SweetalertAsset::register($this);

$this->registerJs($js);
$this->registerCss($css);

$this->title = 'Заказы';
//\yii\widgets\Pjax::begin();
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
        height: 88px; background: #fff; margin: 0 30px; border-bottom: 3px solid #cfcfcf; width: 220px;
        -webkit-box-shadow: 0 10px 5px -4px #000000;
        box-shadow: 0 10px 5px -4px #000000;
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
<div style="margin: 30px 0;">
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
                                    <h1><?=$ordersStats['totalOrders']?></h1>
                                </td>
                                <td>
                                    <h1 style="color: #fff; min-width: 36px; background: #B5B5B5; padding: 5px; line-height: 26px; border-radius: 3px; display: inline-block;"><?=$ordersStats['completedOrders']?></h1>
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
</div>
<?=\common\components\CollectorsWidget::widget([
    'showUnfinished'    =>  $showUnfinished,
    'items'             =>  $collectors
])?>


<?=Html::tag('a', 'За всё время', [
    'href'  =>  \yii\helpers\Url::toRoute([
        '',
        'showDates' =>  'alltime'
    ]),
    'class' =>  'btn btn-default btn-disabled',
    \Yii::$app->request->get("showDates") == 'alltime' ? 'disabled' : '' =>  'true'
]); ?>

<?=\kartik\grid\GridView::widget([
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

        return [];
    },
    'beforeHeader'  =>  '<div style="margin-bottom: -1px;" class="btn-group">
<a href="'.\common\components\RequestHelper::createGetLink('ordersSource', '').'" class="btn btn-default"'.(\Yii::$app->request->get("ordersSource") == 'shop' || \Yii::$app->request->get("ordersSource") == '' ? ' disabled' : '').'>Интернет</a>
<a href="'.\common\components\RequestHelper::createGetLink('ordersSource', 'market').'" class="btn btn-default"'.(\Yii::$app->request->get("ordersSource") == 'market' ? ' disabled' : '').'>Магазин</a>
<a href="'.\common\components\RequestHelper::createGetLink('ordersSource', 'all').'" class="btn btn-default"'.(\Yii::$app->request->get("ordersSource") == 'all' ? ' disabled' : '').'>Все</a>
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
                //TODO: refactor plz
                return '
                <a href="/orders/showorder/'.$model->id.'">
                '.$model->id.'
                </a>
                <br>
                <small>
                    <a href="#" class="deleteOrder">
                        удалить
                    </a>
                </small>';
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
                '<sup><u>'.
                \Yii::$app->formatter->asDate($model->added, 'php:i').
                '</u></sup>';
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
                return '<b>'.$model->deliveryCity.'</b><br>'.$model->deliveryRegion.'';
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
                //TODO: refactor plz
                return '<div style="width: 100%; display: block; position: inherit; height: 100%;"><div style="width: 100%; height: 60%">'.$status1.'</div><div style="width: 100%; height: 40%"><small>'.$status2.'</small></div></div>';
            }
        ],
        [
            'header'    =>  'Сумма',
            'width'     =>  '70px',
            'format'    =>  'html',
            'attribute' =>  'originalSum',
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
                return $model->actualAmount.' грн.';
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
            'width'     =>  '260px',
            'buttons'   =>  [
                'contents'  =>  function($url, $model, $key){
                    return '<a style="margin-top: 1px;" href="/orders/showorder/'.$model->id.'" class="btn btn-default">Содержимое</a>';
                },
                'print'  =>  function($url, $model, $key){
                    return '<a href="/order/printorder?orderID='.$model->id.'" target="_blank" class="btn btn-default glyphicon glyphicon-print"></a>';
                },
                'done'  =>  function($url, $model, $key){
                    return '<button class="btn btn-default doneOrder glyphicon glyphicon-ok'.($model->done == 1 ? ' btn-success' : '').'"'.($model->confirmed == 1 ? '' : ' disabled="disabled"').'></button>';
                },
                'call'  =>  function($url, $model, $key){
                    $subclass = 'btn-default';

                    switch($model->confirmed){
                        case '2':
                            $subclass = 'btn-danger';
                            break;
                        case '1':
                            $subclass = 'btn-success';
                            break;
                    }

                    if($model->callback == '0'){
                        $subclass = 'btn-warning';
                    }

                    return '<button class="btn confirmCall '.$subclass.' glyphicon glyphicon-phone-alt"></button>';
                },
                'changes'   =>  function($url, $model, $key){
                    return '<button class="btn btn-default glyphicon glyphicon-list-alt"></button>';
                },
            ],
            'template'  =>  '<div class="btn-group btn-group-sm">{contents}{print}{changes}{call}{done}</div>'
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
]);

//\yii\widgets\Pjax::end();
?>