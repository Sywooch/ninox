<?php
use bobroid\remodal\Remodal;
use common\models\DeliveryType;
use kartik\dropdown\DropdownX;
use kartik\typeahead\Typeahead;
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\Accordion;
use yii\web\JsExpression;

$this->title = 'Заказ #'.$order->number;
$deliveryType = DeliveryType::find()->select('description')->where(['id' => $order->deliveryType])->scalar();
$deliveryParam = \common\models\DeliveryParam::find()->select('description')->where(['id' => $order->deliveryParam])->scalar();

$customerOrdersSummary = [];

if($customer){
    $customerOrdersSummary = $customer->getOrdersSummary();
}

$typeaheadTemplate = $this->render(
    DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
    .DIRECTORY_SEPARATOR.'layouts'
    .DIRECTORY_SEPARATOR.'search'
    .DIRECTORY_SEPARATOR.'suggestion',
    [
        'toOrder'   =>  true
    ]
);

$customerOrders = [];

if($customer){
    foreach($customer->orders as $oneOrder){
        $customerOrders[] = $this->render('order/_customer_order', [
            'nowOrder'  =>  $order->id,
            'order'     =>  $oneOrder
        ]);
    }
}

$priceRulesDropdown = [];

foreach($priceRules as $rule){
    $priceRulesDropdown[] = [
        'label'     =>  $rule->Name,
        'url'       =>  '#',
        'options'   =>  [
            'onclick'   =>  'usePriceRule(\''.$order->id.'\', \''.$rule->ID.'\'); return false;'
        ]
    ];
}

$css = <<<'CSS'
    body{
        background: #ebecf0;
    }

    .data-items{
        list-style: none;
        line-height: 14px;
        font-size: 13px;
        margin-top: 19px;
    }

    .roundedItem{
        height: 30px;
        width: 30px;
        border-radius: 30px;
        display: inline-block;
        overflow: hidden;
        vertical-align: middle;
        background-size: 70%;
        background-repeat: no-repeat;
        background-position: center;
        cursor: pointer;
        opacity: 0.8;
        margin: 0 2px;
    }

    .roundedItem:hover{
        opacity: 1;
    }

    .icon-chat{
        background-image: url('/img/messages10.svg');
    }

    .icon-heart{
        background-image: url('/img/like80.svg');
    }

    .icon-percent{
        background-image: url('/img/percentage1.svg');
    }

    .background-red{
        background-color: #f22;
    }

    .roundedItem.item-lang{
        font-size: 12px;
        line-height: 28px;
        text-align: center;
        border: 1px solid rgba(200, 200, 200, 0.8);
    }

    .background-green{
        background-color: #2c2;
    }

    .orderItemSelected{
        background: rgba(200, 200, 200, 0.2) !important;
    }
    .block-span{

    }
    .block-span span{
display: block;
line-height: 32px;
font-size: 16px;

    }
    .blue-line{
    width: 58px;
    height: 4px;
    background: #00bfe8;
    margin-bottom: 10px;
    }
    .order-history ul li{
        font-size: 30px;
        color: #dbdbdb;
        line-height: 25px;
    }
    .order-history ul span{
        font-size: 16px;
        vertical-align: middle;
        color: black;
    }
    .order-history ul{
        padding: 0px;
        list-style: inside;
    }

    .order-sum{
    display: inline-block;
    cursor: pointer;
    }

    .order-sum:hover .order-sum-block{
opacity: 1;
visibility: visible;
    }
    .order-sum-block:hover{
    opacity: 1;
visibility: visible;
    }
    .order-sum-block{
opacity: 0;
visibility: hidden;
transition: all .3s ease .15s;
overflow: hidden;
position: absolute;
    width: 320px;
    background: white;
    position: absolute;
    margin-top: -18px;
    border-radius: 5px;
    z-index: 1;
    box-shadow: 0px 0px 10px black;
    padding: 30px;
padding-right: 53px;
    }
    .order-sum-block span{ line-height: 28px;}
    .order-sum-block:hover{
    display: block;
    }
    .order-sum-block .blue-line{
    width: 100%;
    }


    .white{
    background: white;
    border-radius: 5px;
    padding: 25px;
    }

    .accordion{
    padding: 0px;
    }

    .accordion .ui-accordion-header{
    background: none;
    border: none;
    }
    .accordion .ui-accordion-content{
    background: none;
    border: none;
    }

    .status-label{
    margin-left: 20px;
    }

    .on-map{
    display: inline-block !important;
border-radius: 10px;
height: 20px;
line-height: normal !important;
width: 87px;
font-size: 14px !important;
float: left;
    }

    .button-size{
    width: 154px;
    }

CSS;

$js = <<<'JS'
    addItemToOrder = function(order, item){
        swal({
          title: "Добавить товар в заказ",
          text: 'Сколько добавить товара в заказ?',
          type: 'input',
          showCancelButton: true,
          closeOnConfirm: false,
          animation: "slide-from-top",
          inputPlaceholder: "1"
        },
        function(inputValue){
            if (inputValue === false) return false;

            if (inputValue === "") {
                swal.showInputError("Нужно ввести колличество!");
                return false
            }

            $.ajax({
                type: 'POST',
                url: '/goods/addtoorder',
                data: {
                    'orderID': order,
                    'goodID':  item,
                    'itemsCount': inputValue
                },
                success: function(resp){
                    if(resp.status == true){
                        $.pjax.reload({container: '#orderItems-pjax'});

                        swal({
                            type: 'success',
                            title: 'Товар успешно добавлен!',
                            text: 'Товар успешно добавлен к заказу!',
                            timer: 2000
                        })
                    }else{
                        swal({
                            title:"Столько нету",
                            text: "Такого товара на складе осталось <b>" + resp.data.have + " шт.</b>. Добавить сколько есть, или игнорировать?",
                            type: "warning",
                            html: true,
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            cancelButtonColor: "#449D44",
                            confirmButtonText: "Игнорировать!",
                            cancelButtonText: "Добавить сколько есть",
                            closeOnConfirm: true
                        },
                        function(isConfirm){
                            var ignoreCount = "false";

                            if(isConfirm){
                                ignoreCount = "true";
                            }

                            $.ajax({
                                type: 'POST',
                                url: '/goods/addtoorder',
                                data: {
                                    'orderID': order,
                                    'goodID':  item,
                                    'itemsCount': inputValue,
                                    'ignoreMaxCount': ignoreCount
                                },
                                success: function(){
                                    $.pjax.reload({container: '#orderItems-pjax'});
                                }
                            });
                        });
                    }
                }
            });
        });

        $("#addItemToOrder-input").typeahead('val', '');
    }, changePrices = function(id, type){
        var oType = '';
        switch(type){
            case 'opt':
                oType = 'оптовым';
                break;
            case 'rozn':
                oType = 'розничным';
                break;
        }

        swal({
            title: 'Пересчёт заказа',
            text: 'Пересчитать заказ по ' + oType + ' ценам?'
        }, function(isConfirm){
            if(isConfirm){
                $.ajax({
                    type: 'POST',
                    url: '/orders/updateorderprices',
                    data: {
                        'OrderID': id,
                        'type': type
                    },
                    success: function(){
                        $.pjax.reload({container: '#orderItems-pjax'});
                    }
                });
            }
        });
    }, usePriceRule = function(order, rule){
        $.ajax({
            type: 'POST',
            url: '/orders/usepricerule',
            data: {
                'orderID': order,
                'priceRule': rule
            },
            success: function(){
                $.pjax.reload({container: '#orderItems-pjax'});
            }
        });
    }, runCreateInvoice = function(e, order){
        e.currentTarget.innerHTML = '<i class="fa fa-refresh fa-spin"></i>';

        $.ajax({
            type: 'POST',
            url: '/orders/createinvoice/' + order,
            success: function(data){
                e.currentTarget.innerHTML = data;
                $("#novaPoshtaModal #seats").addInputArea();
            }
        });
    }, getSelectedGoods = function(){
        var items = [];

        $.each($(".oneOrderItem.orderItemSelected"), function(index, item){
            items.push($(item).attr('data-key'));
        })

        console.log(items);

        $("#discountSelectedItems").val(JSON.stringify(items));
    }, disableItemInOrder = function(button){
        $.ajax({
            type: 'POST',
            url: '/orders/changeiteminorderstate',
            data: {
                'ID': button.parent().parent().parent().attr('data-key'),
                'param': 'inorder'
            },
            success: function(data){
                var parentRow = button.parent().parent().parent();

                button.toggleClass('btn-warning');
                parentRow.toggleClass('warning');
            }
        });
    }, deleteItemInOrder = function(button){
        $.ajax({
            type: 'POST',
            url: '/orders/changeiteminorderstate',
            data: {
                'ID': button.parent().parent().parent().attr('data-key'),
                'param':    'deleted'
            },
            success: function(data){
                var parentRow = button.parent().parent().parent();

                button.toggleClass('btn-danger');
                parentRow.toggleClass('danger');
            }
        });
    }, refreshItemInOrder = function(button){
        $.ajax({
            type: 'POST',
            url: '/orders/restoreitemdata',
            data: {
                'ID': button.parent().parent().parent().attr('data-key')
            },
            success: function(data){
                $.pjax.reload({container: '#orderItems-pjax'});
            }
        });
    }
    
    $(".oneOrderItem input:checkbox").change(function(){
        setTimeout(getSelectedGoods, 100);
    });


    $("body").on("click", "button.disableGood", function(){
        disableItemInOrder($(this));
    }).on("click", "button.actualizeGood", function(){
        refreshItemInOrder($(this));
    }).on("click", "button.deleteGood", function(){
        deleteItemInOrder($(this));
    }).on('click', ".itemToOrder", function(e){
        addItemToOrder($("#orderInfo")[0].getAttribute('data-attribute-orderID'), e.currentTarget.getAttribute("data-attribute-itemID"));
    }).on('submit', '#invoiceForm', function(e){
        var container = $(this)[0].parentNode;

        container.innerHTML = '<i class="fa fa-refresh fa-spin"></i>';

        $.ajax({
            type: 'POST',
            url: '/orders/createinvoice/' + $("#orderID")[0].getAttribute('data-orderID'),
            data: $(this).serialize(),
            success: function(data){
                container.innerHTML = data;
                $("#novaPoshtaModal #seats").addInputArea();
            }
        });

        e.preventDefault();
    }).on('click', 'tr.oneOrderItem button.editGood', function(){
        var modal = $('[data-remodal-id=goodEditModal]');

        modal[0].innerHTML = '<i class="fa fa-refresh fa-spin"></i>';
        modal.remodal().open();

        var data = JSON.parse($(this)[0].parentNode.parentNode.parentNode.getAttribute('data-itemID'));

        $.ajax({
            type: 'POST',
            data: {
                itemID: data,
                action: 'getEditItemForm'
            },
            success: function(data){
                modal[0].innerHTML = data;
            }
        });
    });

    $("body").on("submit", "form#editGoodForm", function(e){
        e.preventDefault();

        $.ajax({
            type: 'POST',
            data: $(this).serialize(),
            success: function(data){
                $.pjax.reload({container: '#orderItems-pjax'});

                $('[data-remodal-id=goodEditModal]').remodal().close();
            }
        });
    });
    
    $("body").on('click', '#mergeOrdersTable tr.orderRow', function(){
        var order = $(this);
    
        swal({
            title: "Объединить заказы?",
            text: "Переместить товары из текущего заказа в заказ №" + order.find("td[data-col-seq=0]").html() + "?",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Да, переместить",
            cancelButtonText: "отмена",
            closeOnConfirm: false
        },
        function(){
            $.ajax({
                type: 'POST',
                data: {
                    action: 'merge',
                    target: order.attr('data-key')
                },
                success: function(data){
                    swal("Объединены!", "Товары из текущего заказа перемещены в заказ №" + order.find("td[data-col-seq=0]").html(), "success");
                    location.href = '/orders/showorder/' + order.attr('data-key');
                }
            });
        });
    }).on('click', '#customerChangeMoney', function(){
        swal({
            title: "Изменение счёта клиента",
            text: "Какую сумму записать в счёт клиента (сумма будет перезаписана!)",
            type: "input",
            showCancelButton: true,
            closeOnConfirm: false,
            animation: "slide-from-top",
            inputValue: $("#customerMoneyValue").html()
        },
        function(inputValue){
            if (inputValue === false) return false;
            
            if (inputValue === "") {
              swal.showInputError("Необходимо заполнить!");
              return false
            }
            
            $.ajax({
                type: 'POST',
                url: '/customers/manipulate',
                data: {
                    customerID: $("#customerID").prop('data-attribute-customerID'),
                    attribute: 'money',
                    value: inputValue
                },
                success: function(data){
                    swal("Успех!", "У клиента на счету теперь " + inputValue + " грн.", "success");
                }
            });
        });
    });
JS;

$npJS = <<<'JS'
    var openWindow = function(url){
        var myWindow = window.open(url, 'window', 'menubar=no,toolbar=no,status=no,scrollbars=no', true);

        myWindow.onload = function(){
            var script = document.createElement('script');

            script.innerHTML = 'window.print()';

            myWindow.document.appendChild(script);
        }
    }

    $("body").on('click', '.printMark', function(){
        openWindow('https://my.novaposhta.ua/orders/printMarkings/orders[]/' + $(this)[0].getAttribute('ref') + '/type/html/apiKey/5fdddf77cb55decfcbe289063799c67e');
    });

    $("body").on('click', '.printEN', function(){
        openWindow('https://my.novaposhta.ua/orders/printDocument/orders[]/' + $(this)[0].getAttribute('ref') + '/type/html/apiKey/5fdddf77cb55decfcbe289063799c67e');
    });
JS;

$this->registerCss($css);
$this->registerJs($js);
$this->registerJsFile('/js/bootbox.min.js', [
    'depends'   =>  [
        'yii\web\JqueryAsset'
    ]
]);
\bobroid\sweetalert\SweetalertAsset::register($this);

if($order->deliveryType == 2){
    \backend\assets\InputAreaAsset::register($this);

    $this->registerJs($npJS);

    $novaPoshtaModal = new Remodal([
        'cancelButton'		=>	false,
        'confirmButton'		=>	false,
        'addRandomToID'		=>	false,
        'content'			=>	\rmrevin\yii\fontawesome\FA::i('refresh')->addCssClass('fa-spin'),
        'id'				=>	'novaPoshtaModal',
        'options'           =>  [
            'id'                =>  'novaPoshtaModal'
        ],
        'events'			=>	[
            'opened'	        =>	new \yii\web\JsExpression("runCreateInvoice(e, ".$order->id.")")
        ],
    ]);

    echo $novaPoshtaModal->renderModal();

}

$goodEditModal = new Remodal([
    'cancelButton'		=>	false,
    'confirmButton'		=>	false,
    'addRandomToID'		=>	false,
    'content'			=>	\rmrevin\yii\fontawesome\FA::i('refresh')->addCssClass('fa-spin'),
    'options'           =>  [
        'hashTracking'  =>  'false',
        'id'            =>  'goodEditModal'
    ],
    'events'			=>	[
        'opening'	=>	new \yii\web\JsExpression("console.log(e);")
    ],
    'id'				=>	'goodEditModal',
]);

$orderCommentsModal = new Remodal([
    'cancelButton'		=>	false,
    'confirmButton'		=>	false,
    'addRandomToID'		=>	false,
    'options'           =>  [
        //'hashTracking'  =>  'false',
        'id'            =>  'orderCommentsModal'
    ],
    'id'				=>	'orderCommentsModal',
]);


$orderHistory = new \bobroid\remodal\Remodal([
    'cancelButton'		=>	false,
    'confirmButton'		=>	false,
    'closeButton'		=>	true,
    'addRandomToID'		=>	false,
    'content'			=>	$this->render('_order_history', ['order' => $order]),
    'id'				=>	'orderHistory',
    'options'			=>  [
        'class'			=>  'order-history'
    ]

]);


echo $orderCommentsModal->renderModal($this->render('_order_comments', ['order' => $order]));

echo $goodEditModal->renderModal();

echo Html::tag('div', '', [
    'style'                 =>  'display: none',
    'data-attribute-orderID'=>  $order->id,
    'id'                    =>  'orderInfo'
]);

$labels = [];

$paymentLabel = '';

switch($order->paymentType){
    case 1:
        $paymentLabel = Html::tag('span', 'Наложеный платёж ', [
            'class' =>  'label label-danger',
            'style' =>  'display: inline-block; border-radius: 10px; float: left;'
        ]);
        break;
    case 2:
        $paymentLabel = Html::tag('span', 'На карту', [
            'class' =>  'label label-info on-map',

        ]);
        break;
    case 3:
        $paymentLabel = Html::tag('span', 'Наличными', [
            'class' =>  'label label-success',
            'style' =>  'display: inline-block; border-radius: 10px; float: left;'
        ]);
        break;
}

if(!empty($order->sendDate)){
    $labels[] = Html::tag('span', Html::tag('b', 'Заказ отправлен').' '.\Yii::$app->formatter->asDate($order->sendDate, 'php:d.m.Y'), ['class' => 'status-label']);
}

if(!empty($order->nakladna)){
    $labels[] = Html::tag('span', Html::tag('b', 'ТТН').' '.$order->nakladna, ['class' => 'status-label']);
}

if(!empty($order->responsibleUser)){
    $labels[] = Html::tag('span', Html::tag('b', 'Менеджер').' '.$order->responsibleUser->name, ['class' => 'status-label']);
}

$labels[] = $paymentLabel;

?>


<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-8">
        <h1 style="margin-top: 0px;"><?=$order->number?></h1>
        <?=Html::tag('div', implode('', $labels))?>
    </div>

<div class="col-xs-6 col-md-4">
    <?php
    echo Html::a('История клиента', '#', [
        'class' =>  'btn btn-default button-size'
    ]).
Html::a('История заказа', '#orderHistory', [
        'class' =>  'btn btn-default button-size',
        'style' =>  'float: right;'
    ])
    ?>
</div>
</div>
    <hr>
    <div class="row block-span white">
        <div class="col-md-4">
            <div class="blue-line"></div>
            <span data-attribute-customerID="<?=$order->customerID?>" id="customerID"><?=$order->customerName?> <?=$order->customerSurname?></span>
            <span><?=\Yii::$app->formatter->asPhone($order->customerPhone)?></span>
            <span class="order-sum"><b>Сумма заказа <?=!empty($order->actualAmount) ? $order->actualAmount : $order->originalSum?> грн.</b>
                      <div class="order-sum-block">
                          <b>Сума заказа: <?=$order->sumWithoutDiscount?> грн.</b>
                          <?=!empty($order->sumCustomerDiscount) ? Html::tag('span', "Дисконт (-{$order->customer->getDiscount()}%): {$order->sumCustomerDiscount} грн.") : ''?>
                          <?=!empty($order->amountDeductedOrder) ? Html::tag('span', "Списано со счёта: {$order->amountDeductedOrder} грн.") : ''?>
                          <?=!empty($order->sumDiscount) ? Html::tag('span', "Скидка по акции: {$order->sumDiscount} грн.") : ''?>
                          <?=!empty($order->missingItems) ? Html::tag('span', sizeof($order->missingItems)." товаров отсутствует: {$order->missingItemsSum} грн.") : ''?>
                          <div class="blue-line"></div>
                          <b>Сума к оплате <u><?=!empty($order->actualAmount) ? $order->actualAmount : $order->realSum?> грн.</u></b>
                      </div></span>
<?=Remodal::widget([
        'cancelButton'		=>	false,
        'confirmButton'		=>	false,
        'addRandomToID'		=>	false,
        'content'			=>	$this->render('_order_customerInfoEdit', ['model' => $customerForm]),
        'id'                =>	'orderEdit',
        'buttonOptions'     =>  [
            'label' =>  'Редактировать',
            'tag'   =>  'a',
            'class' =>  'btn btn-default button-size',
            'style' =>  'margin-right: 10px; float: left;'
        ],
        'options'           =>   [
            'style' =>  'max-width: 500px;'
        ]
    ]). $paymentLabel
?>
      </div>
      <div class="col-md-4">
          <div class="blue-line"></div>

          <?=Html::tag('span', Html::tag('b', $deliveryType))?>
          <span><?=$order->deliveryCity?>, <?=$order->deliveryRegion?></span>
          <span><?=$deliveryParam?>, <?=$order->deliveryInfo != '' ? ($order->deliveryType == 2 ? 'склад №' : '').$order->deliveryInfo : ''?></span>
          <?=Remodal::widget([
              'cancelButton'		=>	false,
              'confirmButton'		=>	false,
              'addRandomToID'		=>	false,
              'content'			=>	$this->render('_order_deliveryInfoEdit', ['model' => $deliveryForm]),
              'id'                =>	'deliveryInfoEdit',
              'buttonOptions'     =>  [
                  'label' =>  'Редактировать',
                  'tag'   =>  'a',
                  'class' =>  'btn btn-default button-size'
              ],
              'options'           =>   [
                  'style' =>  'max-width: 500px;'
              ]
          ])?>
      </div>
      <div class="col-md-4">
          <div class="blue-line"></div>
          <?php
          if(!empty($order->customer)){
          ?>
              <span><b><?=Html::tag('span', $customer->money, ['id' => 'customerMoneyValue', 'style' => 'display: inline'])?> грн. на счету</b></span>
              <span><?=sizeof($customer->orders) >= 2 ? 'Старый' : 'Новый'?> клиент (<?=\Yii::t('admin', '{count} заказов', ['count' => sizeof($customer->orders)])?> - <?=number_format($customerOrdersSummary['summ'], 0, '.', ' ')?> грн.)</span>
              <span>Возвратов <?=sizeof($customer->returns) >= 1 ? sizeof($customer->returns) : 'нет'?>, <b><?=\Yii::t('admin', '{count} заказов', ['count' => sizeof($customer->notPayedOrders)])?> не оплачено</b></span>
          <?php
          }

          echo Html::a('Изменить', '#', [
              'class'   =>  'btn btn-default button-size',
              'id'      =>  'customerChangeMoney'
          ])
          ?>
      </div>
    </div>
<hr>
<div class="row accordion white">
    <?=Accordion::widget([
        'items' => [
            [
                'header' => Html::tag('span', 'Комментарий клиента', ['class' => 'title']),
                'content' =>$this->render('_review_accordion', ['order' => $order, 'model' => $customerComment]),
            ],
        ],
        'clientOptions' => ['collapsible' => true, (!empty($order->customerComment) ? 'h' : '').'active' => true, 'heightStyle' => 'content'],
    ]);?>
</div>
    <hr>
<div class="row white">
    <div class="col-md-6">
    <div class="">
        <label for="">Печать</label>
        <div class="btn-toolbar">
            <?php
            if($order->deliveryType == 2){
                echo Html::a(Html::img('/img/novapochta.png', ['style' => 'max-height: 19px']), (!empty(trim($order->nakladna)) && $order->nakladna != '-' ? '#novaPoshtaModal' : '#novaPoshtaModal'), ['class' => 'btn btn-default', /*(!empty(trim($order->nakladna)) && $order->nakladna != '-' ? 'disabled' : 'enabled') => 'true'*/]);
            }
            echo Html::a('Накладная', Url::to(['/printer/invoice/'.$order->id]), [
                'class' =>  'btn btn-default'
            ]),
            Html::a('Транспортный лист', Url::to(['/printer/transport_list/'.$order->id]), [
                'class' =>  'btn btn-default'
            ]),
            Html::a('Заказ', Url::to(['/printer/order/'.$order->id]), [
                'class' =>  'btn btn-default'
            ]);

            ?>
        </div>

        <div class="clearfix"></div>

    </div>
    </div>
    <div class="col-md-6">
    <div class="">
        <label for="">Отправка сообщений</label>
        <div class="btn-toolbar ">
            <?php
            echo Html::a('Накладная на почту', '#', [
                'class' =>  'btn btn-default button-size'
            ]);

            if($order->paymentType == 2){
                echo Html::a('Смс с картой', '#', [
                    'class' =>  'btn btn-default button-size'
                ]);
            }

            if($order->deliveryType == 2){
                echo Html::a('Повторно ТТН', '#', [
                    'class' =>  'btn btn-default button-size'
                ]);
            }

            ?>
        </div>

        <div class="clearfix"></div>
    </div>
        </div>
</div>
    <hr>
<div class="row">
    <?=Html::tag('div', Html::a('Сборка заказа', '/orders/sborka/'.$order->ID, ['class' => 'btn btn-lg btn-info
    btn-block']), ['class' => 'col-xs-6'])?>
    <?=Html::tag('div', Html::a('Контроль заказа', '/orders/control/'.$order->ID, ['class' => 'btn btn-lg btn-success
    btn-block']), ['class' => 'col-xs-6'])?>
</div>
    <hr>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li>
                        <div class="row">
                            <div class="col-xs-5" style="color: #777; line-height: 20px; padding-top: 15px; padding-bottom: 15px; vertical-align: middle; margin-right: -25px;">Добавить товар</div>
                            <div class="col-xs-7" style="margin-top: 8px">
                                <?=Typeahead::widget([
                                    'name' => 'search',
                                    'id'    =>  'addItemToOrder-input',
                                    'options' => [
                                        'placeholder'   =>  'Код или название',
                                    ],
                                    'container' =>  [
                                        'style'         =>  'display: inline;  width: 15%; padding: 0; margin: 0; margin-top: 10px;',
                                    ],
                                    'dataset' => [
                                        [
                                            'remote'    =>  [
                                                'url'       =>  '/goods/search?string=QUERY',
                                                'wildcard'  =>  'QUERY'
                                            ],
                                            'limit'     => 10,
                                            'templates' => [
                                                'empty' => Html::tag('div', 'Ничего не найдено', ['class' => 'text-error']),
                                                'suggestion' => new JsExpression("Handlebars.compile('{$typeaheadTemplate}')")
                                            ]
                                        ]
                                    ]
                                ])?>
                            </div>
                        </div>
                    </li>
                    <li><?=$this->render('_transferItemsToOtherOrder', [
                            'order' =>  $order
                        ])?></li>
                    <li>
                        <?=Html::button('Пересчитать заказ '.Html::tag('span', '', [
                                'class' =>  'caret'
                            ]), [
                            'class'         =>  'btn btn-default dropdown-toggle',
                            'style'         =>  'margin-top: 8px',
                            'type'          =>  'button',
                            'data-toggle'   =>  'dropdown',
                            'aria-expanded' =>  'true'
                        ]),
                        DropdownX::widget([
                            'items' =>  [
                                [
                                    'label'     =>  'По оптовым ценам',
                                    'url'       =>  '#',
                                    'options'   =>  [
                                        'onclick'   =>  'changePrices(\''.$order->id.'\', \'opt\')'
                                    ]
                                ],
                                [
                                    'label'     =>  'По розничным ценам',
                                    'url'       =>  '#',
                                    'options'   =>  [
                                        'onclick'   =>  'changePrices(\''.$order->id.'\', \'rozn\')'
                                    ]
                                ],
                                [
                                    'label'     =>  'По акциям',
                                    'items'     =>  $priceRulesDropdown
                                ]
                            ]
                        ])?>
                    </li>
                </ul>
                <ul class="nav navbar-nav pull-right">
                    <li><?=\kartik\editable\Editable::widget([
                            'name'  =>  'discountSize',
                            'header' => 'скидку на товары в заказе',
                            'valueIfNull'   =>  'нет скидки',
                            'value' =>  '',
                            'afterInput'=>function($form, $widget) use (&$order) {
                                return $this->render('_order_setDiscountEditable', ['form' => $form, 'widget' => $widget, 'order' => $order]);
                            },
                            'placement' =>  \kartik\popover\PopoverX::ALIGN_LEFT,
                            'size'=>'md',
                            'preHeader' =>  'Изменить ',
                            'containerOptions'  =>  [
                                'style' =>  'margin-top: 10px;',
                            ],
                            'ajaxSettings'   =>  [
                                'url'   =>  '/orders/setitemsdiscount',
                            ],
                            'pluginOptions'=>[
                                'allowClear'=>true,
                            ],
                            'pluginEvents'  =>  [
                                'editableSubmit'    =>  "function(event, val, form){
                                    location.reload();
                                }"
                            ],
                            'options'=>[
                                'options'=>[
                                    'placeholder'=>'From date'
                                ]
                            ]
                        ])?></li>
                </ul>
            </div>
        </div>
    </nav>
<?php
$thiss = $this;
?>
<?=
$orderHistory->renderModal()
?>
<?=\kartik\grid\GridView::widget([
    'id'    =>  'orderItems',
    'dataProvider'  =>  $itemsDataProvider,
    'containerOptions'       =>  [
        'style'     =>  'overflow: hidden',
    ],
    'tableOptions'  =>  [
        'id'    =>  'orderItems'
    ],
    'pjax'      =>  true,
    'summary'   =>  false,
    'rowOptions'    =>  function($model){
        $classes = [];
        if($model->nezakaz == 1){
            $classes[] = 'danger';
        }

        if($model->nalichie == 0){
            $classes[] = 'warning';
        }

        $classes[] = 'oneOrderItem';

        return [
            'class'     =>  implode(' ', $classes),
            'data-itemID'  =>  $model->itemID
        ];
    },
    'resizableColumns'  =>  false,
    'columns'       =>  [
        [
            'class'     =>  \kartik\grid\CheckboxColumn::className(),
            'vAlign'    =>  \kartik\grid\GridView::ALIGN_MIDDLE,
            'hAlign'    =>  \kartik\grid\GridView::ALIGN_CENTER,
            'rowSelectedClass'  =>  'orderItemSelected'
        ],
        [
            'class'     =>  \kartik\grid\SerialColumn::className()
        ],
        [
            'attribute' =>  'name',
            'header'    =>  'Товар',
            'format'    =>  'html',
            'value'     =>  function($model){
                $ico = \Yii::$app->params['cdn-link'].'/img/catalog/sm/'.$model->photo;

                $anotherOrder = '';

                $anotherOrders = [];

                foreach($model->parentOrders as $parentOrder){
                    $anotherOrders[] = Html::a('Из заказа '.$parentOrder->number, '/orders/showorder/'.$parentOrder->id, [
                        'class'     => 'label label-info no-pjax',
                        'style'     => 'margin-right: 10px; ',
                        'data-pjax' =>  0
                    ]);
                }

                if(!empty($anotherOrders)){
                    $anotherOrder = Html::tag('div', implode('', $anotherOrders));
                }

                return Html::tag('div',
                    Html::img($ico,
                        [
                            'class' => 'img-rounded col-xs-4'
                        ]).
                    Html::tag('div',
                        Html::tag('div',
                            Html::a($model->name.'<br>Код товара: '.$model->good->Code,
                                \yii\helpers\Url::to([
                                    '/goods/view/'.$model->itemID
                                ], [
                                    'data-pjax' => 0
                                ])
                            ).
                            $anotherOrder
                        ), [
                            'class'  =>  'col-xs-8'
                        ]
                    ), [
                        'class' =>   'row-responsive'
                    ]
                );
            }
        ],
        [
            'attribute' =>  'price',
            'header'    =>  'Цена',
            'vAlign'    =>  \kartik\grid\GridView::ALIGN_MIDDLE,
            'hAlign'    =>  \kartik\grid\GridView::ALIGN_CENTER,
            'format'    =>  'html',
            'options'   =>  [
                'style' =>  'width: 120px; text-align: center; vertical-align: middle;'
            ],
            'value'     =>  function($model){
                switch($model->discountType){
                    case '1':
                        return Html::tag('span', $model->price.' грн. '.Html::tag('br').' скидка '.$model->discountSize.' грн.', [
                            'style' =>  'text-align: center; display: block; color: red;'
                        ]).Html::tag('br').Html::tag('s', $model->originalPrice.' грн.');
                        break;
                    case '2':
                        return Html::tag('span', $model->price.' грн. '.Html::tag('br').' скидка '.$model->discountSize.' %.', [
                            'style' =>  'text-align: center; display: block; color: red;'
                        ]).Html::tag('br').Html::tag('s', $model->originalPrice.' грн.');
                        break;
                    default:
                        return $model->price.' грн.';
                }
            }
        ],
        [
            'attribute' =>  'count',
            'header'    =>  'Кол.',
            'vAlign'    =>  \kartik\grid\GridView::ALIGN_MIDDLE,
            'hAlign'    =>  \kartik\grid\GridView::ALIGN_CENTER,
            'options'   =>  [
                'style' =>  'width: 50px;'
            ],
            'value'     =>  function($model){
                return $model->count.' шт.';
            }
        ],
        [
            'header'    =>  'Сумма',
            'vAlign'    =>  \kartik\grid\GridView::ALIGN_MIDDLE,
            'hAlign'    =>  \kartik\grid\GridView::ALIGN_CENTER,
            'options'   =>  [
                'style' =>  'width: 100px;'
            ],
            'value'     =>  function($model){
                return ($model->price * $model->count).' грн.';
            }
        ],
        [
            'header'    =>  'Добавлено',
            'vAlign'    =>  \kartik\grid\GridView::ALIGN_MIDDLE,
            'hAlign'    =>  \kartik\grid\GridView::ALIGN_CENTER,
            'attribute' =>  'added',
            'format'    =>  'html',
            'options'   =>  [
                'style' =>  'width: 100px;'
            ],
            'value'     =>  function($model){
                return \Yii::$app->formatter->asDate($model->added, 'php:d.m.Y H:i:s');
            }
        ],
        [
            'header'    =>  'Остаток',
            'vAlign'    =>  \kartik\grid\GridView::ALIGN_MIDDLE,
            'hAlign'    =>  \kartik\grid\GridView::ALIGN_CENTER,
            'format'    =>  'html',
            'options'   =>  [
                'style' =>  'width: 50px; text-align: center'
            ],
            'value'     =>  function($model){
                if($model->good->count < 0){
                    return Html::tag('span', $model->good->count.' шт.', [
                        'style' =>  'color: red'
                    ]);
                }

                return $model->good->count.' шт.';
            }

        ],
        [
            'header'    =>  'Операции',
            'class'     =>  \kartik\grid\ActionColumn::className(),
            'vAlign'    =>  \kartik\grid\GridView::ALIGN_MIDDLE,
            'hAlign'    =>  \kartik\grid\GridView::ALIGN_CENTER,
            'buttons'   =>  [
                'edit'  =>  function($url, $model){
                    return Html::button(FA::i('pencil'), ['title' => 'Редактировать товар в заказе', 'class' => 'btn btn-default btn-sm editGood']);
                },
                'disable'  =>  function($url, $model){
                    return Html::button(FA::i('power-off'), ['title' => 'Отключить товар в заказе', 'class' => 'btn disableGood btn-default btn-sm'.($model->nalichie == 0 ? ' btn-warning' : '')]);
                },
                'refresh'  =>  function($url, $model){
                    return Html::button(FA::i('refresh'), ['title' => 'Актуализировать товар (сделать таким же, как и в магазине)', 'class' => 'btn actualizeGood btn-default btn-sm']);
                },
                'delete'  =>  function($url, $model){
                    return Html::button(FA::i('trash'), ['title' => 'Удалить товар в заказе', 'class' => 'btn deleteGood btn-default btn-sm'.($model->nezakaz == 1 ? ' btn-danger' : '')]);
                }
            ],
            'template'  =>  Html::tag('div', '{edit}{disable}{refresh}{delete}', ['class' => 'btn-group-vertical']),
            'options'   =>  [
                'style' =>  'width: 40px;'
            ],
        ],
    ]
]);
?>