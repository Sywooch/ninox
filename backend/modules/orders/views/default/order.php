<?php
use kartik\dropdown\DropdownX;
use kartik\typeahead\Typeahead;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\web\JsExpression;

$this->title = 'Заказ #'.$order->id;
$customerOrdersSummary = $customer->getOrdersSummary();
$customerOrders = '';
$typeaheadTemplate = '<a class="typeahead-list-item" onclick="addItemToOrder('.$order->id.', {{ID}})"><div class="row">';
$typeaheadTemplate .= '<div class="col-xs-12 name">{{Name}}</div>';
$typeaheadTemplate .= '<div class="col-xs-12 category"><span class="pull-right">{{categoryname}}</span></div>';
$typeaheadTemplate .= '<div class="col-xs-12 code">Код товара: {{Code}}</div>';
$typeaheadTemplate .= '</div></a>';

foreach($customer->getOrders() as $oneOrder){
    $orderText = 'Заказ №'.$oneOrder->id.' от '.\Yii::$app->formatter->asDate($oneOrder->added, 'php:d.m.Y').' на сумму '.$oneOrder->actualAmount.' грн.';

    if($oneOrder->id == $order->id){
        $orderText = '<span class="text-muted"> '.$orderText.' (текущий)<span>';
    }else{
        $orderClasses = [];

        if($oneOrder->deleted != 0){
            $orderClasses[] = 'text-danger';
        }elseif($oneOrder->done == 1){
            $orderClasses[] = 'text-success';
        }

        $s = '<a href="/orders/showorder/'.$oneOrder->id.'"';

        if(sizeof($orderClasses) >= 1) {
            $s .= ' class="'.implode(' ', $orderClasses).'"';
        }

        $orderText = $s.'>'.$orderText.'</a>';
    }

    $customerOrders[] = $orderText;
}

$priceRulesDropdown = [];

foreach($priceRules as $rule){
    $priceRulesDropdown[] = [
        'label'     =>  $rule->Name,
        'url'       =>  '#',
        'options'   =>  [
            'onclick'   =>  'usePriceRule(\''.$order->id.'\', \''.$rule->ID.'\')'
        ]
    ];
}

$css = <<<'STYLE'
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
        background: rgb(200, 200, 200) !important;
    }
STYLE;

$js = <<<'SCRIPT'
    addItemToOrder = function(order, item){
        bootbox.prompt("Сколько добавить товара?", function(result) {
            if(result !== null){
                $.ajax({
                    type: 'POST',
                    url: '/goods/additemtoorder',
                    data: {
                        'OrderID': order,
                        'itemID':  item,
                        'ItemsCount': result
                    },
                    success: function(data){
                        console.log(data);
                        location.reload();
                    }
                });
            }
        });
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
        bootbox.confirm("Пересчитать заказ по " + oType + " ценам?", function(result){
            if(result){
                $.ajax({
                    type: 'POST',
                    url: '/orders/updateorderprices',
                    data: {
                        'OrderID': id,
                        'type': type
                    },
                    success: function(data){
                        location.reload();
                    }
                });
            }
        });
    }, usePriceRule = function(order, rule){
        bootbox.alert("На стадии доработки...");
    }
SCRIPT;

$this->registerCss($css);
$this->registerJs($js);
$this->registerJsFile('/js/bootbox.min.js', [
    'depends'   =>  [
        'yii\web\JqueryAsset'
    ]
]);
?>
<div class="row">
    <div class="col-xs-4">
        <div>
            <h1>№<?=$order->id?></h1>
        </div>
        <div>
            <h4><?=$order->orderSumm()?> грн. > <?=$order->paymentType()?></h4>
        </div>
    </div>
    <div class="col-xs-4">
        <div>
            <h3 title="Реальная сумма - <?=$order->orderRealSumm()?>">Фактическая сумма - <?=$order->actualAmount?> грн.</h3>
        </div>
        <br style="margin-top: 13px; padding: 0; height: 10px; display: block;">
        <div>
            <h4>Дисконт - <?=$customer->Discount?>%</h4>
        </div>
    </div>
    <div class="col-xs-4">
        <ul class="data-items">
        <?php
            if($order->confirmed == 1){ ?><li>Заказ подтверждён: <?=$order->confirmedDate?></li><?php }
            if($order->done == 1){ ?><li>Заказ собран: <?=$order->doneDate?></li><?php }
            if($order->smsState == 1){ ?><li>Смс с № карты отправлена: <?=$order->smsSendDate?></li><?php }
            if($order->moneyConfirmed == 1){ ?><li>Заказ оплачен: <?=$order->moneyConfirmedDate?></li><?php }
            if($order->nakladnaSendState == 1){ ?><li>ТТН <?=$order->nakladna?> отправлена <?=$order->ttn_date?></li><?php }
        ?>
        </ul>
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
                    <span class="roundedItem icon-percent<?=$customer->Discount > 0 ? ' background-green' : ''?>"></span>
                    <span class="roundedItem item-lang"><?=$customer->lang?></span>
                    <h4><?=$order->customerPhone?></h4>
                </h3>
                <h4><?=$order->deliveryCity?>, <?=$order->deliveryRegion?>, <?=$order->deliveryType()?><?=$order->deliveryInfo != '' ? ' ('.$order->deliveryInfo.')' : ''?> <?php Modal::begin([
                        'header' => 'Редактирование данных заказа',
                        'options'   =>  [
                            'style' =>  'color: black'
                        ],
                        'toggleButton' => [
                            'tag'       =>  'small',
                            'label'     =>  'редактировать',
                            'style'     =>  'cursor: pointer',
                            'class'     =>  'btn btn-link'
                        ],
                        'size'  =>  Modal::SIZE_LARGE,
                    ]);
                    $form = new \yii\bootstrap\ActiveForm();
                    echo $form->field($order, 'customerName'),
                        $form->field($order,'customerSurname'),
                        $form->field($order, 'customerPhone'),
                        $form->field($order, 'customerEmail'),
                        $form->field($order, 'deliveryRegion'),
                        $form->field($order, 'deliveryCity'),
                        $form->field($order, 'deliveryType')->dropDownList(\common\models\DeliveryTypes::getDeliveryTypes()),
                        $form->field($order, 'deliveryInfo'),
                        $form->field($order, 'paymentType')->dropDownList(\common\models\PaymentTypes::getPaymentTypes()),
                        $form->field($order, 'paymentInfo'),
                        $form->field($order, 'coupon');
                    Modal::end(); ?></h4>
            </div>
        </div>
        <div class="col-xs-5">
            <div class="btn-toolbar pull-right">
                <button class="btn btn-default">Накладная</button> <button class="btn btn-default">Транспортный лист</button> <button class="btn btn-default">Заказ</button>
            </div>
            <div style="margin-top: 10px;" class="btn-toolbar pull-right">
                <button class="btn btn-link">Выслать накладную</button> <button class="btn btn-link">Скачать накладную</button>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div>
        <div class="col-xs-7">
            <?=\yii\bootstrap\Collapse::widget([
                'items' =>  [
                    [
                        'label'     =>  'Заказов '.$customerOrdersSummary['count'].' на сумму '.$customerOrdersSummary['summ'].' грн.',
                        'content'   =>  $customerOrders,
                    ],
                ]
            ]);?>
        </div>
        <div class="col-xs-5">
            <button class="btn btn-lg btn-success btn-block">Сборка</button>
        </div>
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
                            <div class="col-xs-7" style="margin-top: 8px"><?=Typeahead::widget([
                                        'name' => 'search',
                                        'options' => [
                                            'placeholder'   =>  'Код или название',
                                        ],
                                        'container' =>  [
                                            'style'         =>  'display: inline;  width: 15%; padding: 0; margin: 0; margin-top: 10px;',
                                        ],
                                        'dataset' => [
                                            [
                                                'remote'    =>  [
                                                    'url'       =>  '/admin/goods/searchgoods?string=QUERY',
                                                    'wildcard'  =>  'QUERY'
                                                ],
                                                'limit'     => 10,
                                                'templates' => [
                                                    'empty' => '<div class="text-error">Ничего не найдено</div>',
                                                    'suggestion' => new JsExpression("Handlebars.compile('".$typeaheadTemplate."')")
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
                    <li><button class="btn btn-default dropdown-toggle" style="margin-top: 8px;" type="button" data-toggle="dropdown" aria-expanded="true">
                            Пересчитать заказ <span class="caret"></span>
                        </button><?=DropdownX::widget([
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
                        ])?></li>
                </ul>
                <ul class="nav navbar-nav pull-right">
                    <li><?=\kartik\editable\Editable::widget([
                            'name'  =>  'name',
                            'header' => 'скидку на товары в заказе',
                            'valueIfNull'   =>  'нет скидки',
                            'value' =>  '',
                            'afterInput'=>function($form, $widget) {
                                return '<div class="form-group"><div>Тип скидки: &nbsp;<div class="btn-group" data-toggle="buttons"><label class="btn btn-default active"><input type="radio" checked="checked" name="priceDiscountType" value="2"> процент</label><label class="btn btn-default"><input type="radio" name="priceDiscountType" value="1">сумма</label></div></div></div><br><div class="form-group"><div><div class="btn-group" data-toggle="buttons"><label class="btn btn-default active"><input type="radio" checked="checked" name="discountRewriteType" value="1">выбраные товары</label><label class="btn btn-default"><input type="radio" name="discountRewriteType" value="2">весь заказ</label></div></div></div>';
                            },
                            'placement' =>  \kartik\popover\PopoverX::ALIGN_LEFT,
                            'size'=>'md',
                            'preHeader' =>  'Изменить ',
                            'containerOptions'  =>  [
                                'style' =>  'margin-top: 10px;',
                            ],
                            'ajaxSettings'   =>  [
                                'url'   =>  '/admin/orders/setorderitemsdiscount',
                                'data'  =>  [
                                    'asdd'  =>  'asdf'
                                ]
                            ],
                            'pluginOptions'=>[
                                'allowClear'=>true,
                            ],
                            'pluginEvents'  =>  [
                                'editableSubmit'    =>  "function(event, val, form){
                                    console.log(val);
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
<?php
\yii\widgets\Pjax::begin()?>
<?=\kartik\grid\GridView::widget([
    'dataProvider'  =>  $itemsDataProvider,
    'containerOptions'       =>  [
        'style'     =>  'overflow: hidden'
    ],
    'summary'   =>  '',
    'rowOptions'    =>  function($model){
        $classes = [];
        if($model->nezakaz == 1){
            $classes[] = 'danger';
        }

        if($model->nalichie == 0){
            $classes[] = 'warning';
        }

        return [
            'class' =>  implode(' ', $classes)
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
            'value'     =>  function($model) use(&$goodsAdditionalInfo){
                $ico = isset($goodsAdditionalInfo[$model->itemID]->ico) && !empty($goodsAdditionalInfo[$model->itemID]->ico) ? 'http://krasota-style.com.ua/img/catalog/sm/'.$goodsAdditionalInfo[$model->itemID]->ico : '';
                return '<div class="row-responsive"><img src="'.$ico.'" class="img-rounded col-xs-4"><div class="col-xs-8"><div>'.$model->name.'</div><div><a href="/admin/goods/showgood/'.$goodsAdditionalInfo[$model->itemID]->ID.'">Код товара: '.$goodsAdditionalInfo[$model->itemID]->Code.'</a></div></div></div>';
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
                        return '<span style="text-align: center; display: block; color: red">'.$model->price.' грн. <br> скидка '.$model->discountSize.' грн.</span><br><s>'.$model->originalPrice.' грн.</s>';
                        break;
                    case '2':
                        return '<span style="text-align: center; display: block; color: red">'.$model->price.' грн. <br> скидка '.$model->discountSize.'%</span><br><s>'.$model->originalPrice.' грн.</s>';
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
            'options'   =>  [
                'style' =>  'width: 50px; text-align: center'
            ],
            'value'     =>  function($model) use(&$goodsAdditionalInfo){
                return $goodsAdditionalInfo[$model->itemID]->count.' шт.';
            }

        ],
        [
            'header'    =>  'Операции',
            'class'     =>  \kartik\grid\ActionColumn::className(),
            'vAlign'    =>  \kartik\grid\GridView::ALIGN_MIDDLE,
            'hAlign'    =>  \kartik\grid\GridView::ALIGN_CENTER,
            'buttons'   =>  [
                'buttons'   =>  function($url, $model, $some){
                    return $this->render('_operations', ['model' => $model]);
                }
            ],
            'template'  =>  '{buttons}',
            'options'   =>  [
                'style' =>  'width: 40px;'
            ],
        ],
    ]
])?>
<?php
\yii\widgets\Pjax::end()?>