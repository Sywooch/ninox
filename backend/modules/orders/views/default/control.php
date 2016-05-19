<?php

use kartik\grid\GridView;
use kartik\grid\SerialColumn;
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\Html;

$this->title = 'Контроль заказа №'.$order->number;

$this->params['breadcrumbs'][] = ['url' => '/orders/control', 'label' => 'Контроль заказа'];
$this->params['breadcrumbs'][] = $this->title;

\bobroid\sweetalert\SweetalertAsset::register($this);

$css = <<<'CSS'
.input-lg{
    padding: 12px;
    font-size: 24px;
    text-align: center;
}
CSS;

$js = <<<'JS'
var setCount = function(data){
    $(".posCount")[0].innerHTML = data.items;
    $(".goodsCount")[0].innerHTML = data.goods;

    if(data.items == 0){
        $(".print-invoice-btn").removeClass('btn-success');
        $(".print-invoice-btn").removeClass('btn-danger');
        $(".print-invoice-btn").toggleClass('btn-success');
    }else{
        $(".print-invoice-btn").removeClass('btn-success');
        $(".print-invoice-btn").removeClass('btn-danger');
        $(".print-invoice-btn").toggleClass('btn-danger');
    }

}, controlItem = function(identifier){
    var itemInput = $("#itemCode");

    $.ajax({
        type: 'POST',
        url: '/orders/control',
        data: {
            action: 'add',
            orderID: $(".page-title")[0].getAttribute("data-attribute-orderID"),
            itemID: itemInput.val()
        },
        success: function(data){
            $.pjax.reload({container: '#orderControlGrid-pjax'});
            itemInput.val('');
            setCount(data);
        },
        error: function(data){
            switch(data.statusText){
                case 'Not Found':
                    swal({
                        title: 'Ошибка!',
                        type: 'error',
                        text: "Товар с идентификатором " + itemInput.val() + " не найден в заказе!"
                    });
                    break;
                case 'Conflict':
                    Messenger().post("Этот товар уже весь подтверждён!");
                    break;
            }
        }
    });
};

$("#itemCode").on('keypress keyup input', function(){
    $(this).val($(this).val().replace(/\D+/, ''));
});

$("body").on('click', ".refresh-btn", function(){
    swal({
      title: 'Начать контроль с начала',
      text: 'Вы уверены что вы хотите сбросить контроль для этого заказа?',
      type: 'warning',
      confirmButtonText: 'Очистить контроль заказа',
      cancelButtonText: 'Отмена',
      closeOnConfirm: false,
      showCancelButton: true
    }, function(){
        $.ajax({
            type: 'POST',
            url: '/orders/control',
            data: {
                action: 'clear',
                orderID: $(".page-title")[0].getAttribute("data-attribute-orderID")
            },
            success: function(data){
                $.pjax.reload({container: '#orderControlGrid-pjax'});
                swal("Очищен!", "Заказ очищен, и вы можете снова его проконтролировать!");
                setCount(data);
            },
            error: function(data){
                console.log(data);
            }
        });
    });
});


$(document).on('keypress', function(e){
    if(e.keyCode == 13 && $("#itemCode").val().length != 0){
        controlItem($("#itemCode").val());
    }
});
JS;

$this->registerJs($js);
$this->registerCss($css);

echo Html::tag('h1', $this->title, ['class' => 'page-title', 'data-attribute-orderID' => $order->ID]),
    Html::tag('h3', 'Осталось проконтролировать '.Html::tag('span', $order->notControlledItemsCount, ['class' => 'posCount']).' позиций и '.Html::tag('span', $order->notControlledGoodsCount, ['class' => 'goodsCount']).' товаров'),
    Html::tag('hr'),
    Html::tag('div',
        Html::input('text', null, null, ['autofocus' => true, 'id' => 'itemCode', 'class' => 'form-control input-lg']), [
            'class' => 'col-xs-5'
        ]).
    Html::tag('div',
        Html::tag('span', 'Сканируйте штрихкод, или введите код вручную и нажмите enter'), [
            'class' => 'col-xs-5',
            'style' =>  'font-size: 18px; text-align: center; vertical-align: middle; line-height: 20px'
        ]).
    Html::tag('div',
        Html::button(FA::i('refresh')->size(FA::SIZE_2X), ['class' => 'btn btn-default btn-lg refresh-btn', 'title' => 'Начать с начала']).'&nbsp;'.
        Html::button(FA::i('print')->size(FA::SIZE_2X), [
            'class' => 'btn btn-lg print-invoice-btn pull-right btn-'.($order->controlled ? 'success' : 'danger'),
            'title' => 'Печатать накладную'
        ]), [
            'class' => 'col-xs-2'
        ]).
    GridView::widget([
        'summary'       =>  false,
        'pjax'          =>  true,
        'id'            =>  'orderControlGrid',
        'options'       =>  [
            'style' =>  'margin-top: 20px; display: inline-block; width: 100%',
            'class' =>  'grid-view'
        ],
        'rowOptions'    =>  function($model){
            return [
                'data-key'  =>  $model->itemID,
                'class'     =>  $model->controlled ? 'success' : ($model->leftControl != $model->originalCount ? 'warning' : '')
            ];
        },
        'columns'       =>  [
            [
                'class'     =>  SerialColumn::className(),
                'width'     =>  '20px',
                'hAlign'    =>  GridView::ALIGN_CENTER,
                'vAlign'    =>  GridView::ALIGN_MIDDLE,
            ],
            [
                'attribute' =>  'photo',
                'width'     =>  '90px',
                'hAlign'    =>  GridView::ALIGN_CENTER,
                'vAlign'    =>  GridView::ALIGN_MIDDLE,
                'format'    =>  'raw',
                'value'     =>  function($model){
                    return Html::img(\Yii::$app->params['cdn-link'].'/img/catalog/sm/'.$model->photo, ['alt' => $model->name]);
                }
            ],
            [
                'attribute' =>  'name',
                'format'    =>  'raw',
                'width'     =>  '560px',
                'hAlign'    =>  GridView::ALIGN_CENTER,
                'vAlign'    =>  GridView::ALIGN_MIDDLE,
                'value'     =>  function($model){
                    return Html::tag('span', $model->name, ['style' => 'display: block']).Html::tag('span', 'ID товара: '.Html::a($model->itemID, '/goods/view/'.$model->itemID));
                }
            ],
            [
                'attribute' =>  'originalCount',
                'width'     =>  '280px',
                'hAlign'    =>  GridView::ALIGN_CENTER,
                'vAlign'    =>  GridView::ALIGN_MIDDLE,
                'value'     =>  function($model){
                    return $model->originalCount.' шт.';
                }
            ],
            [
                'class'     =>  \kartik\grid\DataColumn::className(),
                'width'     =>  '280px',
                'hAlign'    =>  GridView::ALIGN_CENTER,
                'vAlign'    =>  GridView::ALIGN_MIDDLE,
                'value'     =>  function($model){
                    if($model->controlled){
                        return 'Товар в зазаке';
                    }

                    if($model->realyCount == 0){
                        return 'Товар не проконтролирован';
                    }

                    return 'Осталось проконтролировать: '.($model->originalCount - $model->realyCount).' шт.';
                }
            ]
        ],
        'dataProvider'  =>  new \yii\data\ArrayDataProvider([
            'models'    =>  $order->items
        ])
]).
    Html::tag('div', Html::tag('div',
        Html::button(FA::i('refresh')->size(FA::SIZE_2X), [
            'class' => 'btn btn-default btn-lg refresh-btn',
            'title' => 'Начать с начала'
        ])
        .'&nbsp;'.
        Html::button(FA::i('print')->size(FA::SIZE_2X), [
            'class' => 'btn btn-lg print-invoice-btn pull-right btn-'.($order->controlled ? 'success' : 'danger'),
            'title' => 'Печатать накладную'
        ]), ['style' => 'width: 200px']
    ), ['style' => 'margin-left: 450px;']);