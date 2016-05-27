<?php
use backend\modules\orders\widgets\OrdersSearchWidget;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
use yii\helpers\Url;

$js = <<<'JS'
$("body").on('click', '.sms-buttons button', function(){
    $(this).prop('disabled', true).html('<i class="fa fa-refresh fa-spin"></i>');
}).on('click', "a.deleteOrder", function(e){
    deleteOrder(this);
}).on('click', "a.ordersChanges", function(e){
    ordersChanges(this);
}).on('click', "a.restoreOrder", function(e){
    restoreOrder(this);
}).on('click', "button.doneOrder", function(e){
    doneOrder(this);
}).on('click', "button.confirmCall", function(e){
    confirmCall(this);
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
    var orderNode = $(obj.parentNode.parentNode.parentNode.parentNode),
        button = $(obj);

    $.ajax({
        type: 'POST',
        url: '/orders/doneorder',
        data: {
            'OrderID': orderNode.attr('data-key')
        },
        success: function(data){
            button.toggleClass('btn-success');
            changeStatus(orderNode, data.status);
        }
    });
}, changeStatus = function(row, status){
    row.attr('class', '');

    switch(status.id){
        case 1:
        case 3:
            row.toggleClass('warning');
            break;
        case 2:
        case 4:
        case 5:
            row.toggleClass('success');
            break;
        default:
        case 0:
            row.toggleClass('danger');
            break;
    }

    row.find(".mainStatus").html(status.description);
}, confirmCall = function(obj){
    var orderNode = $(obj.parentNode.parentNode.parentNode.parentNode.parentNode),
        button = $(obj);

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
                'OrderID': orderNode.attr('data-key'),
                'confirm': isConfirm
            },
            success: function(data){
                button.attr('class', 'btn btn-default confirmCall').prop('disabled', false);

                switch(data.callback){
                    case 0:
                        orderNode.find("button.doneOrder").prop('disabled', true);
                        break;
                    case 1:
                        button.toggleClass('btn-success');
                        orderNode.find("button.doneOrder").prop('disabled', false);
                        break;
                    case 2:
                    default:
                        button.toggleClass('btn-danger');
                        orderNode.find("button.doneOrder").prop('disabled', true);
                        break;
                }

                changeStatus(orderNode, data.status);
            }
        });

        swal.close();
    });
};

$(document).on("beforeSubmit", ".orderPreviewAJAXForm", function (event) {
    event.preventDefault();
    
    var form = $(this);
   
   if(form.find('.has-error').length) {
      return false;
   }
   
   $.ajax({
       type: "POST",
       url: '/orders/order-preview',
       data: $.extend({action: 'save'}, form.serializeJSON()),
       success: function(response){
           if(response.length == 0 || response == false){
               return false;
           }
  
   
   
           /*var tr = ,
               responsibleUser = tr.find('small.responsibleUser'),
               actualAmount = tr.find('span.actualAmount');
   
           if(responsibleUser != null){
               if(response.responsibleUserID != 0){
                   responsibleUser.html(response.responsibleUserID);
               }else{
                   responsibleUser.remove();
               }
           }else if(response.responsibleUserID != 0){
               var node = document.createElement('small');
               node.innerHTML = response.responsibleUserID;
               node.setAttribute('class', 'responsibleUser');
               tr.find('td[data-col-seq="7"]')[0].appendChild(node);
           }
   
           actualAmount.innerHTML = response.actualAmount + ' грн.';
           
           console.log(tr);
           console.log(tr.find(".kv-expand-row"));*/
           
           $('div[data-attribute-type="ordersGrid"] tr[data-key="' + form.parent().parent().parent().attr('data-key') + '"]').find(".kv-expand-row").trigger('click');
       }
   });

    return false;
});

$(document).on('kvexprow.loaded', 'div[data-attribute-type=ordersGrid]', function(vind, key, extradata){
    $(this).find("tr[data-key=" + extradata + "]").orderPreviewListeners();
});

var sendSms = function(order, type, button){
    $.ajax({
        type: "POST",
        url: '/orders/sms',
        data: {
            orderID: order,
            type: type
        },
        success: function(response){
            button.removeClass('btn-danger').removeClass('btn-default').removeClass('btn-success').prop('disabled', false);

            if(response == 200){
                button.toggleClass('btn-success').html('<i class="fa fa-check"></i>');
            }else{
                button.toggleClass('btn-danger').html('<i class="fa fa-times"></i>');
            }
        }
    });
}

$("body").on('click', "button.sms-order", function(){
    sendSms($(this)[0].parentNode.parentNode.parentNode.getAttribute("data-key"), 'sms', $(this));
}).on('click', "button.sms-card", function(){
    sendSms($(this)[0].parentNode.parentNode.parentNode.getAttribute("data-key"), 'card', $(this));
}).on('click', 'button.informPayment', function(){
    
});
JS;
$css = <<<'CSS'
.orderRow.warning td{
    background: #ff9966 !important;
}

.orderRow.success td{
    background: #66cc00 !important;
}

.orderRow.new td{
    background: #ff9966 !important;
}

.orderRow.danger td{
    background: #ff0000 !important;
}

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

.ordersGrid {

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
        max-width: 134px;
        overflow: hidden;
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
    
    tr.newCustomer td:first-child{
        background-color: #89C4F4 !important;
    }
    
    tr.hasDiscount td:nth-child(2){
        background-color: #EB9532 !important;
    }

    .payment-type{
        font-size: 10px;
        border-radius: 10px;
        padding: 0 10px;
        color: #fff;
    }

    .payment-type.cash-on-delivery{
        background: #f5780e;
    }

    .payment-type.cash-on-card{
        background: #1ba019;
    }

    .payment-type.cash{
        background: #385698;
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


/*echo backend\modules\orders\widgets\OrdersStatsWidget::widget([
    'model' =>  $ordersStatsModel
]),
\backend\widgets\CollectorsWidget::widget([
    'showUnfinished'    =>  $showUnfinished,
    'items'             =>  $collectors
]);*/

echo Html::tag('div', OrdersSearchWidget::widget([
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
                    kvExpandRow({
                        "gridId": selector.substr(1),
                        "hiddenFromExport":true,
                        "detailUrl":"/orders/order-preview",
                        "expandTitle":"Развернуть",
                        "collapseTitle":"Свернуть",
                        "expandAllTitle":"Развернуть все",
                        "collapseAllTitle":"Свернуть все",
                        "rowCssClass":"default",
                        "animationDuration":"slow",
                        "expandOneOnly":false,
                        "enableRowClick":false,
                        "enableCache":true,
                        "rowClickExcludedTags":["A","BUTTON","INPUT"],
                        "collapseAll":false,
                        "expandAll":false,
                        "extraData":[]
                    });
                    $(selector + "-container").removeClass(\'kv-grid-loading\');
                });

                if($(selector)[0].getAttribute(\'settedListeners\') == null){
                    kvExpandRow({
                        "gridId": selector.substr(1),
                        "hiddenFromExport":true,
                        "detailUrl":"/orders/order-preview",
                        "expandTitle":"Развернуть",
                        "collapseTitle":"Свернуть",
                        "expandAllTitle":"Развернуть все",
                        "collapseAllTitle":"Свернуть все",
                        "rowCssClass":"default",
                        "animationDuration":"slow",
                        "expandOneOnly":false,
                        "enableRowClick":false,
                        "enableCache":true,
                        "rowClickExcludedTags":["A","BUTTON","INPUT"],
                        "collapseAll":false,
                        "expandAll":false,
                        "extraData":[]
                    });

                    $(selector)[0].setAttribute(\'settedListeners\', \'true\');
                }
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
            'active'    =>  true,
            'linkOptions'   =>  ['data-url' =>  Url::to(['/orders/showlist', 'showDates' => \Yii::$app->request->get('showDates'), 'ordersSource' => 'internet', 'responsibleUser' => \Yii::$app->request->get("responsibleUser"), 'ordersStatus' => \Yii::$app->request->get("ordersStatus")])]
        ],
        [
            'label'   =>  'Магазин',
            'options'   =>  [
                'id'        =>  'source-local_store',
            ],
            'items'  =>  [
                [
                    'label' =>  'Все',
                    'options'   =>  [
                        'id'    =>  'source-local_store-all'
                    ],
                    'content'   =>  '',
                    'linkOptions'   =>  ['data-url' =>  Url::to(['/orders/showlist', 'showDates' => \Yii::$app->request->get('showDates'), 'ordersSource' => 'market', 'responsibleUser' => \Yii::$app->request->get("responsibleUser"), 'ordersStatus' => \Yii::$app->request->get("ordersStatus")])]
                ],
                [
                    'label' =>  'Хмельницкий',
                    'options'   =>  [
                        'id'    =>  'source-local_store-hm'
                    ],
                    'content'   =>  '',
                    'linkOptions'   =>  ['data-url' =>  Url::to(['/orders/showlist', 'showDates' => \Yii::$app->request->get('showDates'), 'ordersSource' => 'market', 'sourceID' => '2', 'responsibleUser' => \Yii::$app->request->get("responsibleUser"), 'ordersStatus' => \Yii::$app->request->get("ordersStatus")])]
                ],
                [
                    'label' =>  'Троещина',
                    'options'   =>  [
                        'id'    =>  'source-local_store-tr'
                    ],
                    'content'   =>  '',
                    'linkOptions'   =>  ['data-url' =>  Url::to(['/orders/showlist', 'showDates' => \Yii::$app->request->get('showDates'), 'ordersSource' => 'market', 'sourceID' => '1', 'responsibleUser' => \Yii::$app->request->get("responsibleUser"), 'ordersStatus' => \Yii::$app->request->get("ordersStatus")])]
                ],
            ],
            //'linkOptions'   =>  ['data-url' =>  Url::to(['/orders/showlist', 'showDates' => \Yii::$app->request->get('showDates'), 'ordersSource' => 'market', 'responsibleUser' => \Yii::$app->request->get("responsibleUser"), 'ordersStatus' => \Yii::$app->request->get("ordersStatus")])]
        ],
        [
            'label'   =>  'Все',
            'options'   =>  [
                'id'        =>  'source-all',
            ],
            'linkOptions'   =>  ['data-url' =>  Url::to(['/orders/showlist', 'showDates' => \Yii::$app->request->get('showDates'), 'ordersSource' => 'all', 'responsibleUser' => \Yii::$app->request->get("responsibleUser"), 'ordersStatus' => \Yii::$app->request->get("ordersStatus")])]
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

echo Html::tag('script', 'window.onload = function(){ setTimeout(100, $("#ordersSourcesTabs li.active a").click()); }'); //TODO: микрокостыль :D

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