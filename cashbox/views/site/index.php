removeItem<?php

use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\Html;
use kartik\grid\GridView;

$this->title = 'Касса';

$js = <<<'JS'
    Messenger.options = {
        extraClasses: 'messenger-fixed messenger-on-bottom messenger-on-right',
        theme: 'air',
        hideOnNavigate: false
    };

    var addItem = function(item){
        $.ajax({
            type: 'POST',
            url: '/additem',
            data: {
                'itemID': item
            },
            success: function(data){
                $.pjax.reload({container: '#cashboxGrid-pjax'});

                if(data.wholesaleSum >= 500 && data.priceType != 1){
                    changeCashboxType();
                }

                updateSummary(data);

                $(".removeGood > *").on('click', function(e){
                    removeItem(e.currentTarget.parentNode.parentNode.getAttribute('data-attribute-key'));
                });

                $("#itemInput")['0'].value = '';
            },
            error: function (request, status, error) {
                Messenger().post({
                    message: request.responseText.replace(/(.*)\):\s/, ''),
                    type: 'error',
                    showCloseButton: true,
                    hideAfter: 5
                });
            }
        });
    }, removeItem = function(item){
        $.ajax({
            type: 'POST',
            url: '/removeitem',
            data: {
                'itemID': item
            },
            success: function(data){
                $.pjax.reload({container: '#cashboxGrid-pjax'});

                updateSummary(data);

                if(data.wholesaleSum < 500 && data.priceType == 1){
                    changeCashboxType();
                }

                $(".removeGood > *").on('click', function(e){
                    removeItem(e.currentTarget.parentNode.parentNode.getAttribute('data-attribute-key'));
                });
            },
            error: function (request, status, error) {
                Messenger().post({
                    message: request.responseText.replace(/(.*)\):\s/, ''),
                    type: 'error',
                    showCloseButton: true,
                    hideAfter: 5
                });
            }
        });
    }, changeItemCount = function(e){
        $.ajax({
            type: 'POST',
            url: '/changeitemcount',
            data: {
                'itemID': e.currentTarget.getAttribute('data-key'),
                'count': e.currentTarget.value
            },
            success: function(data){
                $.pjax.reload({container: '#cashboxGrid-pjax'});

                if((data.wholesaleSum >= 500 && data.priceType != 1) || (data.wholesaleSum < 500 && data.priceType == 1)){
                    changeCashboxType();
                }

                updateSummary(data);
            },
            error: function (request, status, error) {
                Messenger().post({
                    message: request.responseText.replace(/(.*)\):\s/, ''),
                    type: 'error',
                    showCloseButton: true,
                    hideAfter: 5
                });
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
                url: '/completesell',
                data: {
                    'actualAmount': inputValue
                },
                success: function(data){
                    swal("Успех!", "Заказ на сумму " + inputValue + " грн. успешно оформлен!", "success");
                    window.open('/printinvoice/' + data, '', 'scrollbars=1');
                    location.reload();
                },
                error: function (request, status, error) {
                    swal.close();
                    Messenger().post({
                        message: request.responseText.replace(/(.*)\):\s/, ''),
                        type: 'error',
                        showCloseButton: true,
                        hideAfter: 5
                    });
                }
            });
        });
    }, clearOrder = function(){
        $.ajax({
            type: 'POST',
            url: '/removeitem',
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
                Messenger().post({
                    message: request.responseText.replace(/(.*)\):\s/, ''),
                    type: 'error',
                    showCloseButton: true,
                    hideAfter: 5
                });            }
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
            url: '/changemanager',
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
                        url: '/changemanager',
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
                swal.close();
                Messenger().post({
                    message: request.responseText.replace(/(.*)\):\s/, ''),
                    type: 'error',
                    showCloseButton: true,
                    hideAfter: 5
                });
            }
        });
    }, postponeCheck = function(){
        $.ajax({
            type: 'POST',
            url: '/postponecheck',
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
                Messenger().post({
                    message: request.responseText.replace(/(.*)\):\s/, ''),
                    type: 'error',
                    showCloseButton: true,
                    hideAfter: 5
                });
            }
        });
    }, returnOrder = function(){
        $.ajax({
            type: 'POST',
            url: '/returnorder',
            success: function(data){
                $.pjax.reload({container: '#cashboxGrid-pjax'});

                updateSummary({
                    'sum': 0.00,
                    'toPay': 0.00,
                    'itemsCount': 0
                });

                Messenger().post({
                    message: 'Возврат #' + data + ' совершён',
                    type: 'info',
                    showCloseButton: true,
                    hideAfter: 5
                });
            },
            error: function (request, status, error) {
                Messenger().post({
                    message: request.responseText.replace(/(.*)\):\s/, ''),
                    type: 'error',
                    showCloseButton: true,
                    hideAfter: 5
                });
            }
        });
    };

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


    $("#sellButton").on('click', function(e){
        completeSell();
    });


    $("#clearOrder").on('click', function(e){
        clearOrder();
    });

    $("#returnOrder").on('click', function(e){
        returnOrder();
    });

    $("#itemInput").focus();

    $(document).on('pjax:complete', function() {
        $(".removeGood > *").on('click', function(e){
            removeItem(e.currentTarget.parentNode.parentNode.getAttribute('data-attribute-key'));
        });

        $(".changeItemCount").on('change', function(e){
            changeItemCount(e);
        });

        $("#itemInput").focus();
    });

    $(document).on('keypress', function(e){
        if(e.keyCode == 120){
            completeSell();
        }
    });

    $(document).on('click', '*', function(){
        if($('input:focus').length <= 0){
            $("#itemInput").focus();
        }
    });

    $(document).on('opened', '.remodal', function(){
        $(document).off('click', '*');
    });

    $(document).on('closed', '.remodal', function(){
        $(document).on('click', '*', function(){
            if($('input:focus').length <= 0){
                $("#itemInput").focus();
            }
        });
    });

    $(document).on('click', '*', function(){
        if($('input:focus').length <= 0){
            $("#itemInput").focus();
        }
    });
JS;

$this->registerJs($js);

\bobroid\messenger\ThemeairAssetBundle::register($this);
rmrevin\yii\fontawesome\AssetBundle::register($this);

?>
<div id="mainContent">
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
                            <button class="btn btn-default col-xs-4" id="returnOrder">Возврат</button>
                        </div>
                        <div class="buttonsRow row" style="margin-left: 0; padding: 0">
                            <a class="btn btn-default col-xs-4" href="#defectModal" disabled="disabled">Брак <?=FA::icon('lock')?></a>
                            <button class="btn btn-default col-xs-4" id="changeManager"><?=$manager?></button>
                        </div>
                    </div>
                    <div class="col-xs-3 col-xs-offset-1">
                        <button class="btn btn-default btn-sm" id="postponeCheck" style="margin-bottom: 5px;">Отложить чек</button>
                        <a id="changeCustomer" class="btn btn-default btn-sm" href="#customerModal"><?=$customer ? $customer->Company : '+ клиент'?><?=!empty($customer->phone) ? Html::tag('br').Html::tag('small', $customer->phone) : ''?></a>
                    </div>
                </div>
            </div>
            <div class="col-xs-4 summary <?=\Yii::$app->request->cookies->getValue('cashboxPriceType', 0) == 0 ? 'bg-danger' : 'bg-success'?>">
                <p style="font-size: 14px;">Сумма: <span class="summ"><?=\Yii::$app->cashbox->sum?></span> грн. Скидка: <span class="discountSize"><?=\Yii::$app->cashbox->discountSize?></span> грн.</p>
                <h2 style="font-size: 24px;">К оплате: <span class="toPay"><?=\Yii::$app->cashbox->toPay?></span> грн.</h2>

                <p>Количество товаров: <span class="itemsCount"><?=\Yii::$app->cashbox->itemsCount?></span></p>
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
                    },
                    'width' =>  '40px;'
                ],
                [
                    'class' =>  \yii\grid\SerialColumn::className(),
                    'contentOptions'   =>  [
                        'class' =>  'counter',
                        'width' =>  '40px;'
                    ],
                ],
                [
                    'header'    =>  'Код товара',
                    'value' =>  function($model) use(&$goodsModels){
                        return $goodsModels[$model->itemID]->Code;
                    },
                    'width' =>  '120px'
                ],
                [
                    'attribute' =>  'name'
                ],
                [
                    'header'    =>  'Кол-во',
                    'format'    =>  'raw',
                    'value'     =>  function($model){
                        return Html::input('text', 'changeCount', $model->count, [
                            'class'     =>  'changeItemCount',
                            'data-key'  =>  $model->itemID
                        ]);
                    },
                    'width'     =>  '50px',
                ],
                [
                    'attribute' =>  'price',
                    'header'    =>  'Цена',
                    'width'     =>  '130px',
                    'value'     =>  function($model){
                        return $model->price.' грн.';
                    }
                ],
                [
                    'header'    =>  'Сумма',
                    'width'     =>  '130px',
                    'value'     =>  function($model){
                        return ($model->originalPrice * $model->count).' грн.';
                    }
                ],
                [
                    'header'    =>  'Дисконт',
                    'width'     =>  '100px',
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
                <input id="itemInput" class="form-control" value="">
                <p class="help-block help-block-error"></p>
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="content">
            <div class="left">
                <a class="btn btn-default btn-lg" href="/checks">Чеки</a>
                <a class="btn btn-default btn-lg" href="/sales">Продажи</a>
                <a class="btn btn-default btn-lg" href="/returns">Возвраты</a>
            </div>
            <div class="right">
                <?=Html::button((\Yii::$app->request->cookies->getValue("cashboxPriceType", 0) == 1 ? 'Опт' : 'Розница'), [
                    'class' =>  'btn btn-lg btn-'.(\Yii::$app->request->cookies->getValue("cashboxPriceType", 0) == 0 ? 'danger' : 'success'),
                    'id'    =>  'changeCashboxType',
                ])?>
            </div>
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