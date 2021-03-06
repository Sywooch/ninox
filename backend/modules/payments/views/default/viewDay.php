<?php

use kartik\grid\GridView;
use yii\bootstrap\Html;

\bobroid\sweetalert\SweetalertAsset::register($this);

/**
 * @var int $unconfirmed
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var string $param
 */

$js = <<<'JS'
$("body").on('click', ".confirmPayment", function(){
    var button = $(this); 
    
    swal({
        title: "Подтвердить оплату?",
        text: "Вы уверены, что хотите подтвердить оплату?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Подтвердить!",
        cancelButtonText: "Отмена",
        closeOnConfirm: false
    },
    function(){    
        $.ajax({
            type: 'POST',
            url: '/payments/confirm',
            data: {
                action: 'confirm',
                id: button.parent().parent().attr('data-key')
            },
            success: function(){
                $.pjax.reload({container: '#dailyReportGrid-pjax'});
                swal("Подтверждено!", "Оплата успешно подтверждена!", "success");
            }
        });
    });
}).on('click', '.completeAll', function(){
    var button = $(this); 
    
    swal({
        title: "Подтвердить все оплаты?",
        text: "Вы уверены, что хотите подтвердить все оплаты?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Подтвердить!",
        cancelButtonText: "Отмена",
        closeOnConfirm: false
    },
    function(){    
        $.ajax({
            type: 'POST',
            url: '/payments/confirm-all',
            data: {
                date: location.href.replace(/(htt(.*)\/\D+)|\?(.*)/gm, '')
            },
            success: function(){
                $.pjax.reload({container: '#dailyReportGrid-pjax'});
                swal("Подтверждено!", "Оплаты успешно подтверждены!", "success");
            }
        });
    });
});
JS;

$this->registerJs($js);

$this->title = 'Отчёт за '.\Yii::$app->formatter->asDate($param);

$this->params['breadcrumbs'][] = [
    'label' =>  'Оплаты',
    'url'   =>  '/payments'
];

$this->params['breadcrumbs'][] = [
    'label' =>  'Контроль оплат из магазина и самовывоз',
    'url'   =>  '/payments/control'
];

$this->params['breadcrumbs'][] = $this->title;

$pageHeader = $this->title.'&nbsp;';

if(\Yii::$app->request->get('type')){
    switch(\Yii::$app->request->get('type')){
        case 'shop':
            $pageHeader .= \yii\bootstrap\Html::tag('small', 'из магазина');
            break;
        case 'selfDelivered':
            $pageHeader .= \yii\bootstrap\Html::tag('small', 'самовывоз');
            break;
    }
}

echo \yii\helpers\Html::tag('h1', $pageHeader);
echo GridView::widget([
    'dataProvider'  =>  $dataProvider,
    'summary'       =>  false,
    'id'            =>  'dailyReportGrid',
    'pjax'          =>  true,
    'export'        =>  false,
    'rowOptions'    =>  function($model){
        return [
            'class' => $model->moneyConfirmed == 1 ? 'success' : 'danger'
        ];
    },
    'beforeHeader'  =>  (\Yii::$app->request->get('type') == 'shop' && $unconfirmed != 0) ? Html::tag('div', Html::button('Подтвердить все', ['class' => 'btn btn-default completeAll pull-right', 'style' => 'margin-bottom: 10px;']), ['class' => 'col-xs-12']) : '',
    'columns'       =>  [
        [
            'hAlign'    =>  GridView::ALIGN_CENTER,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'class'     =>  \kartik\grid\SerialColumn::className(),
            'width'     =>  '10px'
        ],
        [
            'hAlign'    =>  GridView::ALIGN_CENTER,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'attribute' =>  'number',
            'label'     =>  'ID',
            'width'     =>  '40px',
            'format'    =>  'html',
            'value'     =>  function($model){
                return \yii\bootstrap\Html::a($model->number, ['/orders/showorder/'.$model->id]);
            }
        ],
        [
            'hAlign'    =>  GridView::ALIGN_CENTER,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'attribute' =>  'added',
            'encodeLabel'=> false,
            'label'     =>  'Время<br>оформления',
            'width'     =>  '50px',
            'value'     =>  function($model){
                    return \Yii::$app->formatter->asDate($model->added, 'php:H:i');
            }
        ],
        [
            'hAlign'    =>  GridView::ALIGN_CENTER,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'label'     =>  'ФИО клиента',
            'attribute' =>  'customerName',
            'value'     =>  function($model){
                return $model->customerSurname.' '.$model->customerName;
            }
        ],
        [
            'class'     =>  \kartik\grid\EditableColumn::className(),
            'editableOptions'   =>  [
                'ajaxSettings'  =>  [
                    'url'   =>  \yii\helpers\Url::to(['/orders/editable-edit'])
                ]
            ],
            'hAlign'    =>  GridView::ALIGN_CENTER,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'width'     =>  '150px',
            'label'     =>  'Фактическая сумма',
            'attribute' =>  'actualAmount',
            'value'     =>  function($model){
                return $model->actualAmount.' грн.';
            }
        ],
        [
            'hAlign'    =>  GridView::ALIGN_CENTER,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'label'     =>  'Город',
            'attribute' =>  'deliveryCity',
        ],
        [
            'hAlign'    =>  GridView::ALIGN_CENTER,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
            'label'     =>  'Телефон клиента',
            'attribute' =>  'customerPhone',
            'width'     =>  '100px',
            'value'     =>  function($model){
                if(empty($model->customerPhone)){
                    return '';
                }

                return \Yii::$app->formatter->asPhone($model->customerPhone);
            }
        ],
        [
            'class'     =>  \kartik\grid\ActionColumn::className(),
            'buttons'   =>  [
                'accept'    =>  function($param, $model){
                    if($model->moneyConfirmed == 1){
                        $moneyCollector = $model->moneyCollector;

                        if(empty($moneyCollector)){
                            $moneyCollector = new \common\models\Siteuser([
                                'name'  =>  '(неизвестно)'
                            ]);
                        }

                        return \yii\bootstrap\Html::button('Оплата подтверждена<br>'.\Yii::$app->formatter->asDatetime($model->moneyConfirmedDate).'<br>пользователь: '.$moneyCollector->name, ['class' => 'btn btn-default', 'disabled' => 'disabled']);
                    }

                    return \yii\bootstrap\Html::button('Подтвердить оплату', ['class' => 'btn btn-default confirmPayment']);
                },
            ],
            'template'  =>  '{accept}'
        ]
    ]
]);