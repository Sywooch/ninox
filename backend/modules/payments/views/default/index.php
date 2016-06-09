<?php

\bobroid\sweetalert\SweetalertAsset::register($this);

$this->title = 'Оплаты';

$this->params['breadcrumbs'][] = $this->title;

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


echo \yii\bootstrap\Html::tag('h1', $this->title);

\yii\widgets\Pjax::begin([
    'id'    =>  'paymentConfirmGrid'
]);

echo \kartik\grid\GridView::widget([
    'dataProvider'  =>  $dataProvider,
    'summary'       =>      false,
    'rowOptions'    =>  function($model){
        return [
            //'class' =>  ($model->moneyConfirmed == 1 ? 'success' : 'warning')
        ];
    },
    'columns'       =>  [
        [
            'class' =>  \yii\grid\SerialColumn::className()
        ],
        [
            'attribute' =>  'nomer_id',
            'label'     =>  'Номер заказа',
            'format'    =>  'raw',
            'value'     =>  function($model){
                if(!empty($model->order)){
                    return \yii\bootstrap\Html::a($model->nomer_id, '/orders/showorder/'.$model->order->ID);
                }

                return $model->nomer_id;
            }
        ],
        [
            'label'     =>  'Дата оформления',
            'value'     =>  function($model){
                if(empty($model->order)){
                    return '(заказ не найден)';
                }

                return \Yii::$app->formatter->asDatetime($model->order->added, 'php: d.m.Y H:i');
            }
        ],
        [
            'label'     =>  'ФИО',
            'value'     =>  function($model){
                if(empty($model->order)){
                    return '(заказ не найден)';
                }

                return $model->order->customerSurname.' '.$model->order->customerName;
            }
        ],
        [
            'attribute' =>  'summ',
            'label'     =>  'Сумма'
        ],
        [
            'label'     =>  'Город',
            'value'     =>  function($model){
                if(empty($model->order)){
                    return '(заказ не найден)';
                }

                return $model->order->deliveryCity;
            }
        ],
        [
            'label'     =>  'Менеджер',
            'value'     =>  function($model){
                if(empty($model->order) && empty($model->order->responsibleUser)){
                    return '';
                }

                return $model->order->responsibleUser->name;
            }
        ],
        [
            'label'     =>  'Телефон',
            'value'     =>  function($model){
                if(empty($model->order)){
                    return '(заказ не найден)';
                }

                return \Yii::$app->formatter->asPhone($model->order->customerPhone);
            }
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