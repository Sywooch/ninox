<?php

use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\Html;
use kartik\grid\GridView;

$this->title = 'Касса';

$js = <<<'SCRIPT'
    Messenger.options = {
        extraClasses: 'messenger-fixed messenger-on-bottom messenger-on-right',
        theme: 'air',
        hideOnNavigate: false
    }

    var addItem = function(item){
        $.ajax({
            type: 'POST',
            url: '/cashbox/additem',
            data: {
                'itemID': item
            },
            success: function(data){

                $.pjax.reload({container: '#cashboxGrid-pjax'});

                updateSummary(data);

                $(".removeGood > *").on('click', function(e){
                    removeItem(e.currentTarget.parentNode.parentNode.getAttribute('data-attribute-key'));
                });
            },
            error: function (request, status, error) {
                console.log(request.responseText);
            }
        });
    }, removeItem = function(item){
        $.ajax({
            type: 'POST',
            url: '/cashbox/removeitem',
            data: {
                'itemID': item
            },
            success: function(data){
                $.pjax.reload({container: '#cashboxGrid-pjax'});

                updateSummary(data);

                $(".removeGood > *").on('click', function(e){
                    removeItem(e.currentTarget.parentNode.parentNode.getAttribute('data-attribute-key'));
                });
            },
            error: function (request, status, error) {
                console.log(request.responseText);
            }
        });
    }, changeItemCount = function(e){
        $.ajax({
            type: 'POST',
            url: '/cashbox/changeitemcount',
            data: {
                'itemID': e.currentTarget.getAttribute('data-key'),
                'count': e.currentTarget.value
            },
            success: function(data){
                $.pjax.reload({container: '#cashboxGrid-pjax'});

                updateSummary(data);
            },
            error: function (request, status, error) {
                console.log(request.responseText);
            }
        });
    }, completeSell = function(){
        var paymentSum = $(".summ")[0].innerHTML;

        var s = swal({
            title: "Введите сумму к оплате",
            text: "Текущая сумма: " + paymentSum,
            html: true,
            type: "input",
            showCancelButton: true,
            closeOnConfirm: false,
            animation: "slide-from-top",
            inputPlaceholder: "Сумма к оплате",
            inputValue: paymentSum,
            cancelButtonText: 'Отмена',
            confirmButtonText: 'Оформить заказ'
        },
        function(inputValue){
            if (inputValue === false) return false;

            if (inputValue === "") {
                swal.showInputError("Поле нельзя оставлять пустым!");
                return false
            }

            swal({
                title: "Оформляем заказ...",
                allowEscapeKey: false,
                showConfirmButton: false
            });

            $.ajax({
                type: 'POST',
                url: '/cashbox/completesell',
                data: {
                    'actualAmount': inputValue
                },
                success: function(data){
                    swal("Успех!", "Заказ на сумму " + inputValue + " грн. успешно оформлен!", "success");
                    location.reload();
                },
                error: function (request, status, error) {
                    console.log(request.responseText);
                }
            });
        });
    }, clearOrder = function(){
        $.ajax({
            type: 'POST',
            url: '/cashbox/removeitem',
            data: {
                'itemID': 'all'
            },
            success: function(data){
                $.pjax.reload({container: '#cashboxGrid-pjax'});

                updateSummary({
                    'sum': 0.00,
                    'toPay': 0.00,
                    'itemsCount': 0
                });

                Messenger().post({
                    message: 'Текущий заказ очищен',
                    type: 'info',
                    showCloseButton: true,
                    hideAfter: 5
                });
            },
            error: function (request, status, error) {
                console.log(request.responseText);
            }
        });
    }, updateSummary = function(data){
        if(data.sum !== undefined){
            $(".summ")[0].innerHTML = data.sum;
        }

        if(data.toPay !== undefined){
            $(".toPay")[0].innerHTML = data.toPay;
        }

        if(data.toPay !== undefined){
            $(".itemsCount")[0].innerHTML = data.itemsCount;
        }
    }, changeManager = function(e){
        $.ajax({
            type: 'POST',
            url: '/cashbox/changemanager',
            data: {
                'action': 'showList'
            },
            success: function(data){
                swal({
                    title:  "Сменить продавца?",
                    text:   data,
                    html:   true,
                    showConfirmButton: false,
                    showCancelButton: true,
                    cancelButtonText: 'Отмена'
                });

                $(".managersButtons > *").on('click', function(e){
                    $.ajax({
                        type: 'POST',
                        url: '/cashbox/changemanager',
                        data: {
                            'action': 'change',
                            'manager': e.currentTarget.getAttribute('manager-key')
                        },
                        success: function(){
                            $("#changeManager")[0].innerHTML = e.currentTarget.innerHTML;

                            Messenger().post({
                                message: 'Менеджер изменён на <b>' + e.currentTarget.innerHTML + '</b>',
                                type: 'info',
                                showCloseButton: true,
                                hideAfter: 5
                            });
                        },
                        error: function (request, status, error) {
                            console.log(request.responseText);
                        }
                    });
                });
            },
            error: function (request, status, error) {
                console.log(request.responseText);
            }
        });
    }, postponeCheck = function(){
        $.ajax({
            type: 'POST',
            url: '/cashbox/postponecheck',
            success: function(data){
                $.pjax.reload({container: '#cashboxGrid-pjax'});

                updateSummary({
                    'sum': 0.00,
                    'toPay': 0.00,
                    'itemsCount': 0
                });

                Messenger().post({
                    message: 'Чек #' + data + ' отложен',
                    type: 'info',
                    showCloseButton: true,
                    hideAfter: 5
                });
            },
            error: function (request, status, error) {
                console.log(request.responseText);
            }
        });
    }

    $("#itemInput").on('keypress', function(e){
        e.currentTarget.value = e.currentTarget.value.replace(/\D+/, '');

        if((e.keyCode == 13) && e.currentTarget.value != ''){
            addItem(e.currentTarget.value);
        }
    });

    $("#itemInput").on('keyup', function(e){
        e.currentTarget.value = e.currentTarget.value.replace(/\D+/, '');
    });

    $(".removeGood > *").on('click', function(e){
        removeItem(e.currentTarget.parentNode.parentNode.getAttribute('data-attribute-key'));
    });

    $(".changeItemCount").on('change', function(e){
        changeItemCount(e);
    });

    $("#changeManager").on('click', function(e){
        changeManager(e);
    });

    $("#postponeCheck").on('click', function(e){
        postponeCheck();
    });

    $(document).on('pjax:complete', function() {
        $(".removeGood > *").on('click', function(e){
            removeItem(e.currentTarget.parentNode.parentNode.getAttribute('data-attribute-key'));
        });

        $(".changeItemCount").on('change', function(e){
            changeItemCount(e);
        });
    });

    $("#sellButton").on('click', function(e){
        completeSell();
    });


    $("#clearOrder").on('click', function(e){
        clearOrder();
    });

    $(document).on('keypress', function(e){
        if(e.keyCode == 120){
            completeSell();
        }
    });
SCRIPT;

$this->registerJs($js);

\bobroid\messenger\ThemeairAssetBundle::register($this);
rmrevin\yii\fontawesome\AssetBundle::register($this);

?>

<div class="no-padding header">
    <div class="content row">
        <div class="buttonsContainer col-xs-8">
            <div class="col-xs-2" style="padding: 0; margin-right: 12px;">
                <button class="btn btn-default btn-big" id="sellButton">Продажа (F9)</button>
            </div>
            <div class="manyButtons col-xs-10 row" style="margin-left: 0; padding: 0">
                <div class="col-xs-8" style="margin-left: 0; padding: 0">
                    <div class="buttonsRow row" style="margin-left: 0; padding: 0">
                        <a class="btn btn-default col-xs-4" href="#writeOffModal" disabled="disabled">Списание <?=FA::icon('lock')?></a>
                        <button class="btn btn-default col-xs-4" id="clearOrder">Очистить заказ</button>
                        <a class="btn btn-default col-xs-4" href="#returnModal" disabled="disabled">Возврат</a>
                    </div>
                    <div class="buttonsRow row" style="margin-left: 0; padding: 0">
                        <a class="btn btn-default col-xs-4" href="#defectModal" disabled="disabled">Брак <?=FA::icon('lock')?></a>
                        <button class="btn btn-default col-xs-4" id="changeManager"><?=$manager?></button>
                    </div>
                </div>
                <div class="col-xs-3 col-xs-offset-1">
                    <button class="btn btn-default btn-sm" id="postponeCheck" style="margin-bottom: 5px;">Отложить чек</button>
                    <a class="btn btn-default btn-sm" href="#customerModal" disabled="disabled">+ клиент</a>
                </div>
            </div>
        </div>
        <div class="col-xs-4 summary <?=\Yii::$app->request->cookies->getValue('cashboxPriceType', 0) == 0 ? 'bg-danger' : 'bg-success'?>">
            <p style="font-size: 14px;">Сумма: <span class="summ"><?=$order->sum?></span> грн. Скидка: <span class="discountPercent"><?=$order->discountPercent?></span>% (<span class="discountSize"><?=$order->discountSize?></span> грн.)</p>
            <h2 style="font-size: 24px;">К оплате: <span class="toPay"><?=$order->toPay?></span> грн.</h2>

            <p>Количество товаров: <span class="itemsCount"><?=count($order->items)?></span></p>
        </div>
    </div>
</div>
<div class="content main">
    <?=GridView::widget([
        'pjax'          =>  true,
        'dataProvider'  =>  $orderItems,
        'rowOptions'    =>  function($model) use(&$goodsModels){
            return [
                'data-attribute-key'  =>  $goodsModels[$model->itemID]->ID
            ];
        },
        'emptyTextOptions'  =>  [
            'class' =>  'emptyText'
        ],
        'columns'       =>  [
            [
                'contentOptions'   =>  [
                    'class' =>  'removeGood'
                ],
                'format'    =>  'html',
                'value'     =>  function($model){
                    return FA::icon('times')->size(FA::SIZE_LARGE);
                }
            ],
            [
                'class' =>  \yii\grid\SerialColumn::className(),
                'contentOptions'   =>  [
                    'class' =>  'counter'
                ],
            ],
            [
                'value' =>  function($model) use(&$goodsModels){
                    return $goodsModels[$model->itemID]->Code;
                }
            ],
            [
                'attribute' =>  'name'
            ],
            [
                'format'    =>  'raw',
                'value'     =>  function($model){
                    return Html::input('text', 'changeCount', $model->count, [
                        'class'     =>  'changeItemCount',
                        'data-key'  =>  $model->itemID
                    ]);
                },
                'contentOptions'    =>  [
                    'style' =>  'width: 40px;'
                ]
            ],
            [
                'attribute' =>  'price',
                'value'     =>  function($model){
                    return $model->price.' грн.';
                }
            ],
            [
                'value'     =>  function($model){
                    return ($model->originalPrice * $model->count).' грн.';
                }
            ],
            [
                'value'     =>  function($model){
                    return '-'.$model->discountSize.($model->discountType == '2' ? ' грн.' : '%');
                }
            ]
        ],
        'id'            =>  'cashboxGrid',
        'summary'       =>  false,
        'emptyText'     =>  false,
    ])?>
    <div class="form-group" style="margin-top: -20px;">
        <div class="inputField">
            <input id="itemInput" class="form-control" value="" type="text">
            <p class="help-block help-block-error"></p>
        </div>
    </div>
</div>
<div class="footer">
    <div class="content">
        <div class="left">
            <a class="btn btn-default btn-lg" href="/cashbox/checks">Чеки</a>
            <a class="btn btn-default btn-lg" href="/cashbox/sales">Продажи</a>
        </div>
        <div class="right">
            <?=Html::button((\Yii::$app->request->cookies->getValue("cashboxPriceType", 0) == 1 ? 'Опт' : 'Розница'), [
                'class' =>  'btn btn-lg btn-'.(\Yii::$app->request->cookies->getValue("cashboxPriceType", 0) == 0 ? 'danger' : 'success'),
                'id'    =>  'changeCashboxType',
            ])?>
        </div>
    </div>
</div>

<?php
$customerInfoModal = new \bobroid\remodal\Remodal([
    'id'            =>  'customerModal',
    'addRandomToID' =>  false,
    'confirmButton' =>  false,
    'cancelButton'  =>  false
]);

echo $customerInfoModal->renderModal($this->render('_customer_modal'));
?>