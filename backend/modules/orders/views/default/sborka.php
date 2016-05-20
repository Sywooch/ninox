<?php

use yii\bootstrap\Html;

$this->title = 'Сборка заказа №'.$order->number;

$js = <<<'JS'
var setDefaultState = function(item){
    console.log('set state to default for item');
    console.log(item);

    item.find('.image').removeClass('access');
    item.find('.image').removeClass('denied');

    item.find("button.toOrder").html("В ЗАКАЗ").removeClass('gray-button').removeClass('green-button').addClass('green-button').removeAttr('disabled');
    item.find("button.notFoundItem").css('display', 'block').html("НЕ МОГУ НАЙТИ").removeClass('gray-button').removeClass('red-button').addClass('red-button').removeAttr('disabled');
    item.find(".fromOrder").css('display', 'none');
}, parseField = function(field){
    var parent = field.parent().parent().parent(),
        data = parent.attr('data-key');

        return {itemID: data, count: parent.find(".inOrderCount").val()};
}, saveItemsCount = function(fields){
    var data = {},
        createdData = new Array;

    data.action = 'saveItemsCount';

    $.each(fields, function(key, item){
        createdData.push(parseField($(item)));
    })

    data.fields = createdData;

    $.ajax({
        type: 'POST',
        data: data,
        success: function(){
            return true;
        },
        error: function(){
            return false;
        }
    });
}

$("body").on('click', 'button.saveCount', function(){
    saveItemsCount($(this));
}).on('click', 'button.saveOrder', function(){
    saveItemsCount($(".list-view .saveCount"));
}).on('click', 'button.notFoundItem', function(){
    if($(this).disabled){
        return false;
    }

    var toOrderButton = $(this).parent().find("button.toOrder"),
        parent = $(this).parent().parent().parent();


    $.ajax({
        type: 'POST',
        data: {
            action: 'changeNotFound',
            itemID: parent.attr('data-key')
        },
        success: function(data){
            parent.find("div.image").toggleClass('denied');

            switch(data){
                case 1:
                    toOrderButton.removeClass('green-button').addClass('gray-button').attr('disabled', true);
                    break;
                case 0:
                default:
                    setDefaultState(parent);
                    break;
            }
        }
    });
}).on('click', 'button.toOrder, button.fromOrder', function(e){
    if($(this).disabled){
        return false;
    }
    
    var button = $(this), 
        notFoundButton = button.parent().find("button.notFoundItem"),
        parent = button.parent().parent().parent();

    $.ajax({
        type: 'POST',
        data: {
            action: 'changeInOrder',
            itemID: parent.attr('data-key')
        },
        success: function(data){
            parent.find("div.image").toggleClass('access');

            switch(data){
                case 1:
                    button.html("В ЗАКАЗЕ " + parent.find(".inOrderCount").val() + " ШТ");
                    notFoundButton.removeClass('red-button').addClass('gray-button').addClass('gray-button').attr('disabled', true);

                    if($(e.currentTarget).hasClass('fromOrder')){
                        parent.find(".fromOrder").css('display', 'none');
                        notFoundButton.css('display', 'block');
                    }else{
                        parent.find(".fromOrder").css('display', 'block');
                        notFoundButton.css('display', 'none');
                    }
                    break;
                case 0:
                default:
                    setDefaultState(parent);
                    break;
            }
        }
    });
});
JS;

$this->registerJs($js);

echo Html::tag('div', Html::tag('div', Html::a('К заказам', '/', [
    'class' =>  'yellow-button small-button button',
]).
    Html::tag('div',
        Html::tag('span', $order->number),
        ['class' => 'order-number']).Html::button('Сохранить', [
        'type'  =>  'submit',
        'class' =>  'green-button small-button saveOrder button',
        'id'    =>  'submit'
    ]),
    [
        'class' => 'header'
    ]).
    \yii\widgets\ListView::widget([
        'dataProvider'  =>  new \yii\data\ActiveDataProvider([
            'query' =>  $order->getItems(false),
            'pagination'    =>  [
                'pageSize'  =>  0
            ]
        ]),
        'summary'   =>  false,
        'itemOptions'   =>  [
            'class'     =>  'typical-block',
        ],
        'itemView'  =>  function($model){
            return $this->render('_sborka_item', [
                'item'  =>  $model
            ]);
        }
    ]),
    [
        'class' => 'sborka'
    ]);

