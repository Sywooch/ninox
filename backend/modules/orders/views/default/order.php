<?php
use bobroid\remodal\Remodal;
use common\models\DeliveryType;
use kartik\dropdown\DropdownX;
use kartik\typeahead\Typeahead;
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
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
            items.push(item.attr('data-key'));
        })

        $("#discountSelectedItems").val(JSON.stringify(items));
    }, disableItemInOrder = function(button){
        $.ajax({
            type: 'POST',
            url: '/orders/changeiteminorderstate',
            data: {
                'ID': button[0].parentNode.parentNode.parentNode.getAttribute('data-key'),
                'param': 'inorder'
            },
            success: function(data){
                var parentRow = $(button[0].parentNode.parentNode.parentNode);

                button.toggleClass('btn-warning');
                parentRow.toggleClass('warning');
            }
        });
    }, deleteItemInOrder = function(button){
        $.ajax({
            type: 'POST',
            url: '/orders/changeiteminorderstate',
            data: {
                'ID': button[0].parentNode.parentNode.parentNode.getAttribute('data-key'),
                'param':    'deleted'
            },
            success: function(data){
                var parentRow = $(button[0].parentNode.parentNode.parentNode);

                button.toggleClass('btn-danger');
                parentRow.toggleClass('danger');
            }
        });
    }, refreshItemInOrder = function(button){
        $.ajax({
            type: 'POST',
            url: '/orders/restoreitemdata',
            data: {
                'ID': button[0].parentNode.parentNode.parentNode.getAttribute('data-key')
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

/**
 * TODO: желательно блок ниже переместить в какое-нибудь другое, более предназначеное для этого место
 */

$orderHistory = [];

if($order->confirmed == 1){
    $orderHistory[] = Html::tag('li', "Заказ подтверждён: {$order->confirmedDate}");
}

if($order->done == 1){
    $orderHistory[] = Html::tag('li', "Заказ собран: {$order->doneDate}");
}

if($order->smsState == 1){
    $orderHistory[] = Html::tag('li', "Смс с № карты отправлена: {$order->smsSendDate}");
}

if($order->moneyConfirmed == 1){
    $orderHistory[] = Html::tag('li', "Заказ оплачен: {$order->smsSendDate}");
}

if($order->nakladnaSendState == 1){
    $orderHistory[] = Html::tag('li', "ТТН {$order->nakladna} отправлена {$order->nakladnaSendDate}");
}

/**
 * ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
 */

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

echo $orderCommentsModal->renderModal($this->render('_order_comments', ['order' => $order]));

echo $goodEditModal->renderModal();

echo Html::tag('div', '', [
    'style'                 =>  'display: none',
    'data-attribute-orderID'=>  $order->id,
    'id'                    =>  'orderInfo'
])
?>
<div class="row">
    <div class="col-xs-4">
        <div>
            <h1 id="orderID" data-orderID="<?=$order->id?>">№<?=$order->number?> <?=Html::a(FA::i('comment'.(sizeof($order->comments) <= 0 ? '-o' : '')), '#orderCommentsModal', ['class' => 'roundedItem btn btn-default', 'title' => 'Комментарий к заказу', 'style' => 'padding: 3px;'])?></h1>
        </div>
        <div>
            <h4><?=$order->orderSumm()?> грн. > <?=$deliveryType?></h4>
        </div>
    </div>
    <div class="col-xs-4">
        <div>
            <h3 title="Реальная сумма - <?=$order->orderRealSumm()?>">Фактическая сумма - <?=$order->actualAmount?> грн.</h3>
        </div>
        <br style="margin-top: 13px; padding: 0; height: 10px; display: block;">
        <div>
            <h4>Дисконт - <?=$customer->discount?>%</h4>
        </div>
    </div>
    <div class="col-xs-4">
        <?=Html::tag('ul', implode($orderHistory), ['class' => 'data-items'])?>
    </div>
</div>
<hr>
<div class="row">
    <div>
        <div class="col-xs-7">
            <div>
                <h3><?=$order->customerName?> <?=$order->customerSurname?>
                    <?php Modal::begin([
                        'header' => 'Комментарии к клиенту '.$order->customerName.' '.$order->customerSurname,
                        'options'   =>  [
                            'style' =>  'color: black'
                        ],
                        'toggleButton' => [
                            'tag'       =>  'span',
                            'label'     =>  '',
                            'class'     =>  'roundedItem icon-chat background-red'
                        ],
                        'size'  =>  Modal::SIZE_DEFAULT,
                    ]); ?>

                    <?php Modal::end(); ?>
                    <!--<span class="roundedItem icon-heart"></span>-->
                    <?=Html::tag('span', '', ['class' => 'roundedItem icon-percent'.($customer->discount > 0 ? ' background-green' : '')]),
                        Html::tag('span', $customer->lang, ['class' => 'roundedItem item-lang']),
                        Html::tag('h4', \Yii::$app->formatter->asPhone($order->customerPhone))?>
                </h3>
                <h4><?=$order->deliveryCity?>, <?=$order->deliveryRegion?>, <?=$deliveryType?> <small>(<?=$deliveryParam?>)</small><?=$order->deliveryInfo != '' ? ' ('.$order->deliveryInfo.')' : ''?></h4>
                <?=Remodal::widget([
                    'cancelButton'		=>	false,
                    'confirmButton'		=>	false,
                    'addRandomToID'		=>	false,
                    'content'			=>	$this->render('_order_edit', ['order' => $order]),
                    'id'                =>	'orderEdit',
                    'buttonOptions'     =>  [
                        'label' =>  'редактировать',
                        'tag'   =>  'a',
                        'style' =>  'margin-bottom: 20px; display: inline-block'
                    ],
                ]);?>
            </div>
        </div>
        <div class="col-xs-5">
            <div class="btn-toolbar pull-right">
                <?php
                if($order->deliveryType == 2){
                    echo Html::a(Html::img('/img/novapochta.png', ['style' => 'max-height: 34px']), (!empty(trim($order->nakladna)) && $order->nakladna != '-' ? '#novaPoshtaModal' : '#novaPoshtaModal'), ['class' => 'btn btn-default', /*(!empty(trim($order->nakladna)) && $order->nakladna != '-' ? 'disabled' : 'enabled') => 'true'*/]);
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
            <div style="margin-top: 10px;" class="btn-toolbar pull-right">
                <button class="btn btn-link">Выслать накладную</button>
                <?=Html::a('Скачать накладную', "/export/excel/{$order->id}", [
                    'class' =>  'btn btn-link'
                ])?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div>
        <div class="col-xs-7">
            <?php
            if(!empty($customerOrders)){
                echo \yii\bootstrap\Collapse::widget([
                    'items' =>  [
                        [
                            'label'     =>  'Заказов '.$customerOrdersSummary['count'].' на сумму '.$customerOrdersSummary['summ'].' грн.',
                            'content'   =>  $customerOrders,
                        ],
                    ]
                ]);
            }
            ?>
        </div>
        <?=Html::tag('div', Html::a('Сборка', '/orders/sborka/'.$order->ID, ['class' => 'btn btn-lg btn-success btn-block']), ['class' => 'col-xs-5'])?>
    </div>
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
                        'style'     => 'margin-right: 10px',
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