<?php

use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\Html;
use kartik\grid\GridView;

$this->title = 'Касса';

$js = <<<JS
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
 
                /*if(data.wholesaleSum >= 500 && data.priceType != 1){
                    console.log('recalc');
                    changeCashboxType();
                }*/

                summary.update(data);

                $("#itemInput").val("");                
                
                $("html, body").animate({ scrollTop: $(document).height() }, "slow");
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

                summary.update(data);

                /*if(data.wholesaleSum < 500 && data.priceType == 1){
                    changeCashboxType();
                }*/
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
    }, 
    newChangeItemCount = function(input){
        $.ajax({
            type: 'POST',
            url: '/changeitemcount',
            data: {
                'itemID': input.attr('data-key'),
                'count': input.val()
            },
            success: function(data){
                $.pjax.reload({container: '#cashboxGrid-pjax'});

                /*if((data.wholesaleSum >= 500 && data.priceType != 1) || (data.wholesaleSum < 500 && data.priceType == 1)){
                    changeCashboxType();
                }*/

                summary.update(data);
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
    },
    changeItemCount = function(e){
        $.ajax({
            type: 'POST',
            url: '/changeitemcount',
            data: {
                'itemID': e.currentTarget.getAttribute('data-key'),
                'count': e.currentTarget.value
            },
            success: function(data){
                $.pjax.reload({container: '#cashboxGrid-pjax'});

                /*if((data.wholesaleSum >= 500 && data.priceType != 1) || (data.wholesaleSum < 500 && data.priceType == 1)){
                    changeCashboxType();
                }*/

                summary.update(data);
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
        var paymentSum = $(".toPay").html();

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
            url: '/clear-order',
            success: function(data){
                $.pjax.reload({container: '#cashboxGrid-pjax'});

                summary.clear();

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
                });
            }
        });
    },
    summary = {
        selector: '.summary',
        wholesaleClass: 'success',
        retailClass: 'danger',
        buttonSelector: '#changeCashboxType',
        update: function(data){
            if(data.sum !== undefined){
                $(this.selector).find(".summ").html(data.sum);
            }

            if(data.sumToPay !== undefined){
                $(this.selector).find(".toPay").html(data.sumToPay);
            }

            if(data.sumToPay !== undefined){
                $(this.selector).find(".wholesale-sum").html(data.wholesaleSum);
            }

            if(data.sumToPay !== undefined){
                $(this.selector).find(".discount").html(data.discountSum);
            }

            if(data.sumToPay !== undefined){
                $(this.selector).find(".itemsCount").html(data.itemsCount);
            }
        },
        clear: function(){
            this.update({
                'sum': 0.00,
                'sumToPay': 0.00,
                'itemsCount': 0,
                'discountSum': 0,
                'wholesaleSum': 0
            });

            this.setWholesale();
        },
        setWholesale: function(){
            $(this.selector)
                .removeClass('bg-' + this.retailClass)
                .addClass('bg-' + this.wholesaleClass);
                
            $(this.buttonSelector)
                .removeClass('btn-' + this.retailClass)
                .addClass('btn-' + this.wholesaleClass)
                .html('Опт');
        },
        setRetail: function(){
            $(this.selector)
                .removeClass('bg-' + this.wholesaleClass)
                .addClass('bg-' + this.retailClass);
            
            $(this.buttonSelector)
                .removeClass('btn-' + this.wholesaleClass)
                .addClass('btn-' + this.retailClass)
                .html('Розница');
        }
    },
    postponeCheck = function(){
        $.ajax({
            type: 'POST',
            url: '/postponecheck',
            success: function(data){
                $.pjax.reload({container: '#cashboxGrid-pjax'});

                summary.clear();

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

                summary.clear();

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

    $('body').on('click', ".removeGood > *", function(e){
        removeItem($(this).parent().parent().attr('data-attribute-key'));
    }).on('change', ".changeItemCount", function(e){
        newChangeItemCount($(this));
    }).on('click', "#postponeCheck", function(e){
        postponeCheck();
    }).on('click', "#sellButton", function(e){
        completeSell();
    }).on('click', "#clearOrder", function(e){
        clearOrder();
    }).on('click', "#returnOrder", function(e){
        returnOrder();
    }).on('keypress', "#itemInput", function(e){
        $(this).val($(this).val().replace(/\D+/, ''));

        if((e.keyCode == 13) && $(this).val() != ''){
            addItem($(this).val());
        }
    }).on('click', "#changeManager", function(){
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
    }).on('click', ".managersButtons > *", function(e){
        var button = $(this);
        
        $.ajax({
            type: 'POST',
            url: '/changemanager',
            data: {
                'action': 'change',
                'manager': button.attr('manager-key')
            },
            success: function(){
                $("#changeManager").html(button.html());

                Messenger().post({
                    message: 'Менеджер изменён на <b>' + button.html() + '</b>',
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

    $(document).on('pjax:complete', function() {
        $("#itemInput").focus();
    }).on('keypress', function(e){
        if(e.keyCode == 120){
            completeSell();
        }
    }).on('click', '*', function(){
        if($('input:focus').length <= 0){
            $("#itemInput").focus();
        }
    }).on('opened', '.remodal', function(){
        $(document).off('click', '*');
    }).on('closed', '.remodal', function(){
        $(document).on('click', '*', function(){
            if($('input:focus').length <= 0){
                $("#itemInput").focus();
            }
        });
    }).on('click', '*', function(){
        if($('input:focus').length <= 0){
            $("#itemInput").focus();
        }
    });
    
    $("#itemInput").focus();
JS;

$this->registerJs($js);

$css = <<<'CSS'
#mainContent .header .summary.bg-success .wholesale-block{
    display: none;
}
#mainContent .header .summary.bg-danger .wholesale-block{
    display: block;
}
CSS;

$this->registerCss($css);

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
                            <button class="btn btn-default col-xs-4" id="changeManager"><?=!empty($manager) && !$manager->isNewRecord ? $manager->name : 'Не выбрано'?></button>
                        </div>
                    </div>
                    <div class="col-xs-3 col-xs-offset-1">
                        <button class="btn btn-default btn-sm" id="postponeCheck" style="margin-bottom: 5px;">Отложить чек</button>
                        <?=Html::a((!empty($customer) ? $customer->Company.Html::tag('br').Html::tag('small', $customer->phone) : '+ клиент'), '#customerModal', ['id' => 'changeCustomer', 'class' => 'btn btn-default btn-sm'])?>
                    </div>
                </div>
            </div>
            <div class="col-xs-4 summary <?=$priceType == 0 ? 'bg-danger' : 'bg-success'?>">
                <p style="font-size: 14px;">Сумма: <?=Html::tag('span', $sum, ['class' => 'summ'])?> грн. Скидка: <?=Html::tag('span', $discountSize, ['class' => 'discount'])?> грн.</p>
                <h2 style="font-size: 24px;">К оплате: <?=Html::tag('span', $toPay, ['class' => 'toPay'])?> грн.</h2>
                <p class="wholesale-block">Сумма по опту: <?=Html::tag('span', $wholesaleSum, ['class' => 'wholesale-sum', 'style' => 'display: inline'])?></p>
                <p>Количество товаров: <?=Html::tag('span', $itemsCount, ['class' => 'itemsCount'])?></p>
            </div>
        </div>
    </div>
    <div class="content main">
        <?=GridView::widget([
            'pjax'          =>  true,
            'responsive'    =>  false,
            'resizableColumns'=>  false,
            'dataProvider'  =>  $orderItems,
            'rowOptions'    =>  function($model){
                $return = [
                    'data-attribute-key'    =>  $model->itemID,
                ];

                if($model->price <= 0){
                    $return['class'] = 'danger';
                }

                return $return;
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
                    'value'     =>  function(){
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
                    'value' =>  function($model){
                        return $model->good->Code;
                    },
                    'width' =>  '120px'
                ],
                [
                    'attribute' =>  'name',
                    'header'    =>  'Наименование товара'
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
                    'format'    =>  'raw',
                    'value'     =>  function($model){
                        $return = "$model->salePrice грн.";

                        if($model->good->priceForPiece){
                            $return .= Html::tag('small',
                                '('.\Yii::$app->formatter->asPrice($model->priceForPiece).' грн. за шт.)',
                                ['style' => 'display: block;']
                            );
                        }

                        return $return;
                    }
                ],
                [
                    'header'    =>  'Сумма',
                    'width'     =>  '130px',
                    'value'     =>  function($model){
                        return "$model->sum грн.";
                    }
                ],
                [
                    'header'    =>  'Дисконт',
                    'width'     =>  '100px',
                    'value'     =>  function($model){
                        return "$model->discountValue грн.";
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
                <?=Html::button(($priceType == 1 ? 'Опт' : 'Розница'), [
                    'class' =>  'btn btn-lg btn-'.($priceType == 1 ? 'success' : 'danger'),
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
