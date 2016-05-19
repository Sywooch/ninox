<?php

\bobroid\sweetalert\SweetalertAsset::register($this);

$js = <<<'JS'
$("body").on('click', ".pGroup button.confirm", function(){
    var elem = $(this);

    swal({
      title: "Подтвердить оплату?",
      text: "Вы уверены, что хотите подтвердить оплату? Эту операцию нельзя обратить!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#33C155",
      confirmButtonText: "Подтвердить!",
      cancelButtonText: "Отмена",
      closeOnConfirm: false
    },
    function(){    
        $.ajax({
            type: 'POST',
            url: '/payments',
            data: {
                action: 'confirm',
                id: elem.parent().parent().parent().attr('data-key')
            },
            success: function(){
                $.pjax.reload({container: '#paymentConfirmGrid'});
            }
        });
        
        swal("Оплата подтверждена!", "Оплата успешно подтверждена!", "success");
    });
}).on('click', ".pGroup button.remove", function(){
    var elem = $(this);

    swal({
      title: "Удалить запрос?",
      text: "Вы уверены, что хотите удалить запрос на подтверждение оплаты? Эту операцию нельзя обратить!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Удалить!",
      cancelButtonText: "Отмена",
      closeOnConfirm: false
    },
    function(){    
        $.ajax({
            type: 'POST',
            url: '/payments',
            data: {
                action: 'delete',
                id: elem.parent().parent().parent().attr('data-key')
            },
            success: function(){
                $.pjax.reload({container: '#paymentConfirmGrid'});
            }
        });
      swal("Удалено!", "Запрос на подтверждение оплаты успешно удалён!", "success");
    });
});
JS;

$this->registerJs($js);

$this->title = 'Подтверждение оплат';


echo \yii\bootstrap\Html::tag('h1', 'Оплаты');

\yii\widgets\Pjax::begin([
    'id'    =>  'paymentConfirmGrid'
]);

echo \kartik\grid\GridView::widget([
    'dataProvider'  =>  $dataProvider,
    'summary'       =>      false,
    'columns'       =>  [
        [
            'class' =>  \yii\grid\SerialColumn::className()
        ],
        [
            'attribute' =>  'nomer_id',
            'label'     =>  'Номер заказа'
        ],
        [
            'attribute' =>  'summ',
            'label'     =>  'Сумма'
        ],
        [
            'attribute' =>  'data_oplaty',
            'label'     =>  'Дата'
        ],
        [
            'attribute' =>  'sposoboplaty',
            'label'     =>  'Способ оплаты'
        ],
        [
            'class'     =>   \kartik\grid\ActionColumn::className(),
            'template'  =>   \yii\bootstrap\Html::tag('div', '{confirm}&nbsp;{delete}', ['class' => 'btn-group pGroup', 'style' => 'width: 80px']),
            'buttons'   =>   [
                'confirm'   =>  function(){
                    return \yii\bootstrap\Html::button(\rmrevin\yii\fontawesome\FA::i('check'), ['class' => 'btn btn-success confirm']);
                },
                'delete'    =>   function(){
                    return \yii\bootstrap\Html::button(\rmrevin\yii\fontawesome\FA::i('trash'), ['class' => 'btn btn-danger remove']);
                },
            ]
        ]
    ]
]);

\yii\widgets\Pjax::end();