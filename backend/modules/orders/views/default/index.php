<?php
use backend\modules\orders\widgets\OrdersSearchWidget;
use bobroid\remodal\Remodal;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\Accordion;

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
    var container   = $(item).parent().parent().parent(),
        orderID     = $(container).attr('data-key');

    swal({
        title:  'Подождите, пожалуйста...',
        text:   'Возвращаем товары со склада...',
        type: 'info',
        showConfirmButton: false,
        closeOnConfirm: false
    });

    $.ajax({
        type: 'POST',
        url: '/orders/restore',
        data: {
            'orderID': orderID
        },
        success: function(data){
            swal("Восстановлен!", "Заказ успешно восстановлен!", "success");
            container.remove();
            
            var a = document.querySelector('.kv-expand-detail-row[data-key="' + orderID + '"]');
            
            if(a !== undefined && a != null){
                a.remove();
            }
        }
    });
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
    var button = $(obj),
        orderNode = button.closest('tr'),
        actualAmount = parseFloat(orderNode.find('.actualAmount').text().replace(/\s.*/, '')),
        cardSms = orderNode.find('.sms-card');

    if(actualAmount <= 0){
        swal("Ошибка!", "Введите сумму к оплате!", "error");
        return false;
    }

    cardSms.prop('disabled', 'disabled');

    $.ajax({
        type: 'POST',
        url: '/orders/doneorder',
        data: {
            'OrderID': orderNode.attr('data-key')
        },
        success: function(data){
            button.toggleClass('btn-success');
            changeStatus(orderNode, data.status);
            if(data.done && data.sms.result > 0){
                swal({
                    title: data.sms.result == 200 ? "Успех!" : "Ошибка!",
                    text: "SMS " + data.sms.message + (data.sms.result == 200 ? "" : " не") + " было отправлено!",
                    type: data.sms.result == 200 ? "success" : "error",
                });
                cardSms.toggleClass('success', data.sms.result == 200);
            }
            cardSms.prop('disabled', false);
        }
    });
}, changeStatus = function(row, status){
    row.removeClass('warning success danger notCalled');

    switch(status.id){
        case 1:
        case 3:
            row.addClass('warning');
            break;
        case 2:
        case 4:
        case 5:
            row.addClass('success');
            break;
        default:
        case 0:
            row.addClass('danger');
            break;
    }

    row.find(".mainStatus").html(status.description);
}, confirmCall = function(obj){
    var button = $(obj),
        orderNode = button.closest('tr');

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

$(document).on("beforeSubmit", ".orderPreviewAJAXForm", function(event){
    event.preventDefault();

    var form = $(this);

    if(form.find('.has-error').length){
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

            var orderNode = form.closest('table.kv-grid-table').find('.orderRow[data-key="' + form.closest('tr.kv-expand-detail-row').data('key') + '"]'),
            actualAmount = orderNode.find('.actualAmount');
            actualAmount.text((response.actualAmount == '' ? 0 : response.actualAmount) + ' грн.');
            orderNode.find(".kv-expand-row").trigger('click');
        }
    });

    return false;
});

$(document).on("beforeSubmit", "#payment-confirm-form", function(event){
    event.preventDefault();

    var form = $(this);

    if(form.find('.has-error').length){
        return false;
    }

    $.ajax({
        type: "POST",
        url: '/orders/payment-confirm-form',
        data: $.extend({action: 'save'}, form.serializeJSON()),
        success: function(response){
            form.closest('.remodal').remodal().close();
        }
    });

    return false;
});

$(document).on('kvexprow.loaded', 'div[data-attribute-type=ordersGrid]', function(vind, key, extradata){
    $(this).find("tr[data-key=" + extradata + "]").orderPreviewListeners(extradata);
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
            button.removeClass('btn-danger btn-default btn-success').prop('disabled', false);

            if(response == 200){
                button.toggleClass('btn-success').html('<i class="fa fa-check"></i>');
            }else{
                button.toggleClass('btn-danger').html('<i class="fa fa-times"></i>');
            }
        }
    });
}

$("body").on('click', "button.sms-order", function(){
    sendSms($(this).closest('tr[data-key]').data('key'), 'sms', $(this));
}).on('click', "button.sms-card", function(){
    sendSms($(this).closest('tr').data('key'), 'card', $(this));
}).on('click', 'button.btn-inform-payment', function(){
    $('#payment-confirm-form').find('#paymentconfirmform-ordernumber').val($(this).data('number'));
}).on('click', 'input.btn-cancel', function(){
    var obj = $(this),
    orderNode = obj.closest('table.kv-grid-table').find('.orderRow[data-key="' + obj.closest('tr.kv-expand-detail-row').data('key') + '"]');
    orderNode.find(".kv-expand-row").trigger('click');
}).on('submit', '#extendedSearch', function(e){
    e.preventDefault();
    $("#searchResults").tab('show');
    $("#searchResults").css('display', 'block');
    url = '/orders/showlist?ordersSource=search&context=true&' + $(this).serialize();
    $.pjax({url: url, container: '#ordersGridView_search-pjax', push: false, replace: false, timeout: 10000,scrollTo: true});
});
JS;
$css = <<<'CSS'
.orderRow.warning td{
    background: rgba(255, 253, 88, 0.62) !important;
}

.orderRow.success td{
    background: #66cc00 !important;
}

.orderRow.danger td{
    background: #ff0000 !important;
}

.orderRow.new td{
    background: #ff9966 !important;
}

.orderRow.notCalled td{
    background: #ff9966 !important;
}
.kv-expand-detail-row, .kv-expand-detail-row:hover{
    background: #fff !important;
    border: 3px solid #000;
    border-top: none;
    border-collapse: collapse;
}

.kv-expand-detail-row td{
    padding: 0 !important;
}

.kv-expand-detail-row table{
    table-layout: fixed;
}

.kv-expand-detail-row table td{
    vertical-align: middle !important;
}

.kv-expand-detail-row table td:first-child{
    width: 515px;
}

.kv-expand-detail-row table td:last-child{
    width: 180px;
}

.kv-expand-detail-row .btn.btn-lg{
    border: 2px solid;
}

.kv-expand-detail-row #orderpreviewform-actualamount{
    width: 110px;
}

.kv-expand-detail-row .btn-save, .kv-expand-detail-row .btn-cancel{
    margin: 0 25px 10px 0;
    width: 120px;
}

.kv-expand-detail-row .btn-save, .kv-expand-detail-row .btn-inform-payment{
    border-color: #5cb85c;
    color: #5cb85c;
}

.kv-expand-detail-row .btn-cancel{
    border-color: #ff6c00;
    color: #ff6c00;
}

.kv-expand-detail-row .sms-order{
    margin-left: 10px;
}

.kv-expand-detail-row .form-group{
    padding: 0 3px;
    vertical-align: middle;
}

.kv-expand-detail-row .form-group.money-collector,
.kv-expand-detail-row .form-group.ttn-send-date{
    font-size: 12px;
}

.kv-expand-detail-row .form-group.money-collector > div,
.kv-expand-detail-row .form-group.ttn-send-date > div{
    display: inline-block;
}

.kv-expand-detail-row .form-group.money-collector:before,
.kv-expand-detail-row .form-group.ttn-send-date:before{
    content: '';
    width: 12px;
    height: 12px;
    border-radius: 6px;
    background: #5cb85c;
    margin: 0 10px;
    display: inline-block;
    vertical-align: 50%;
}

.kv-expand-detail-row .form-control{
    vertical-align: middle;
}

.kv-expand-detail-row table tr{
    height: 70px;
}

.kv-expand-detail-row table td + td{
    border-left: 65px solid transparent;
}

.kv-expand-detail-row table td label{
    font-weight: normal;
    font-size: 12px;
    padding-right: 15px;
}

.kv-expand-detail-row table td:first-child label{
    width: 155px;
    padding-left: 35px;
}

.kv-expand-detail-row table td:first-child .form-group:first-child select{
    width: 180px;
}

.kv-expand-detail-row table td:first-child .form-group:nth-child(2) select{
    width: 130px;
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

    button.success{
        background: #56b356;
    }
   
CSS;

\bobroid\sweetalert\SweetalertAsset::register($this);
\kartik\depdrop\DepDropAsset::register($this);

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
]),*/



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
Accordion::widget([
    'items' => [
        [
            'header' => Html::tag('span', 'Расширеный поиск по заказам', ['class' => 'title']),
            'content' =>Html::tag('div',
                $this->render('_extendedSearch', ['model' => $searchModel]),
                [
                    'class' =>  'row'
                ]
            ),
        ],
    ],
    'clientOptions' => ['collapsible' => true, 'active' => true, 'heightStyle' => 'content'],
]),
\backend\widgets\CollectorsWidget::widget([
    'showUnfinished'    =>  $showUnfinished,
    'dateFrom'          =>  $collectorsData['dateFrom'],
    'dateTo'          =>  $collectorsData['dateTo'],
    'items'             =>  $collectors
]);

if(\Yii::$app->request->get('ordersStatus') == 'delivery'){
    echo Html::tag('div', Html::a(FA::i('print').' Печать', Url::to(array_merge(['/printer/delivery-list'], \Yii::$app->request->get())), ['class' => 'btn btn-default', 'target' => '_blank']), ['class' => 'col-xs-12']),
        Html::tag('br');
}

echo Html::tag('br'),
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

$modal = new Remodal([
    'id'            =>  'orderChanges',
    'cancelButton'  =>  false,
    'confirmButton' =>  false,
    'addRandomToID' =>  false,
]);

$paymentMessage = new Remodal([
    'cancelButton'	=>	false,
    'confirmButton'	=>	false,
    'addRandomToID'	=>	false,
    'id'            =>  'payment-confirm-form',
    'content'       =>  $this->render('_payment_confirm'),
]);

echo $modal->renderModal().
    $paymentMessage->renderModal();