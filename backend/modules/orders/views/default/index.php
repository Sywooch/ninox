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
};



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
	height: 35px;
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
	margin-right: 0;
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

#searchResults{
    display: none;
}

.active #searchResults{
    display: block;
}

.nav.nav-tabs{
    border-bottom: 4px solid #cfcfcf;
}

.nav.nav-tabs li{
    margin-right: 9px;
    margin-bottom: 0;
    max-height: 37px;
    overflow: hidden;
}

.nav.nav-tabs li a{
    color: #000;
    background-color: #f8f8f8;
    font-size: 14px;
    padding: 10px 25px;
    font-family: Arial;
    margin-bottom: -3px;
}

.nav.nav-tabs li.active a{
    background-color: #cfcfcf;
}
CSS;

\bobroid\sweetalert\SweetalertAsset::register($this);

$this->registerJs($js);
$this->registerCss($css);

$accordionJs = <<<'JS'
(function( $ ){
    $.fn.menuAccordion = function(options) {
        if(options == undefined || options == null){
            options = {};
        }

        var defaultOptions = {
                panelWidth:  '340',
                labelWidth: '100',
                animationDelay: '300'
            },
            container = this,
            activePanel = container.find('.panel:first');

        options = $.extend(defaultOptions, options);

        $(activePanel).addClass('active');

        this.delegate('.panel', 'click', function(e){
            if(!$(this).is('.active')){
                $(activePanel).animate({width: options.labelWidth + "px"}, options.animationDelay);
                $(this).animate({width: options.panelWidth + "px"}, options.animationDelay);
                container.find('.panel').removeClass('active');
                $(this).addClass('active');
                activePanel = this;
            };
        });

        this.delegate('input', 'keypress', function(e){
            if(e.keyCode == 13){
                e.preventDefault();
                $("#searchResults").tab('show');
                $("#searchResults").css('display', 'block');
                url = '/orders/showlist?ordersSource=search&context=true&' + e.currentTarget.name + '=' + e.currentTarget.value;
                $.pjax({url: url, container: '#ordersGridView_search-pjax', push: false, replace: false, timeout: 10000,scrollTo: true});

            }
        });
    };
})( jQuery );

$("#accordion").menuAccordion();
JS;

$this->registerJs($accordionJs, 3);

$this->title = 'Заказы';
?>
<style>
    .ordersStatsContainer{
        height: 100px;
    }

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
        font-family: "Open Sans", serif;
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
<div class="ordersStatsContainer">
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
</div>

<?=\backend\widgets\CollectorsWidget::widget([
    'showUnfinished'    =>  $showUnfinished,
    'items'             =>  $collectors
]),
Html::tag('div', OrdersSearchWidget::widget([
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
]), [
    'style' =>  'margin: 0 10px 10px 0',
    'class' =>  'row well well-lg'
]),
\kartik\tabs\TabsX::widget([
    'id'            =>  'ordersSourcesTabs',
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

            if($("#ordersGridView_internet-pjax").length > 0){
                setListeners("#ordersGridView_internet");
            }

            if($("#ordersGridView_market-pjax").length > 0){
                setListeners("#ordersGridView_market");
            }

            if($("#ordersGridView_all-pjax").length > 0){
                setListeners("#ordersGridView_all");
            }

            if($("#ordersGridView_search-pjax").length > 0){
                setListeners("#ordersGridView_search");
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
            'content'   =>  $this->context->runAction('showlist', ['context' => true, 'ordersSource' => 'internet']),
            'active'    =>  true,
            'linkOptions'   =>  ['data-url' =>  Url::to(['/orders/showlist', 'showDates' => \Yii::$app->request->get('showDates'), 'ordersSource' => 'internet'])]
        ],
        [
            'label'   =>  'Магазин',
            'options'   =>  [
                'id'        =>  'source-local_store',
            ],
            'linkOptions'   =>  ['data-url' =>  Url::to(['/orders/showlist', 'showDates' => \Yii::$app->request->get('showDates'), 'ordersSource' => 'market'])]
        ],
        [
            'label'   =>  'Все',
            'options'   =>  [
                'id'        =>  'source-all',
            ],
            'linkOptions'   =>  ['data-url' =>  Url::to(['/orders/showlist', 'showDates' => \Yii::$app->request->get('showDates'), 'ordersSource' => 'all'])]
        ],
        [
            'label'     =>  'Результаты поиска',
            'linkOptions'   =>  ['id' =>  'searchResults'],
            'content'   =>  $this->context->runAction('showlist', ['context' => true, 'ordersSource' => 'search']),
            'options'   =>  [
                'id'    =>  'source-search_results'
            ]
        ]
    ]
]);

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