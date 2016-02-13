<?php
use yii\bootstrap\Html;

$this->title = 'Продажи';

$js = <<<'JS'
var showSaleDetails = function(e){
    $.ajax({
        type: 'POST',
        url: '/getsaledetails',
        data: {
            'orderID':  e.currentTarget.getAttribute('data-attribute-id')
        },
        success: function(data){
            $("[data-remodal-id=saleDetails] > div").replaceWith(data);
            $("[data-remodal-id=saleDetails]").remodal().open();
        },
        error: function (request, status, error) {
            console.log(request.responseText);
        }
    });
}, updateTable = function(date){
    $.pjax({url: '/sales?smartfilter=' + date, container: '#salesTable-pjax'});
}, editOrder = function(e){
    $.ajax({
        type: 'POST',
        url: '/loadorder',
        data: {
            'orderID':  e.currentTarget.getAttribute('data-attribute-id')
        },
        success: function(data){
            if(data){
                location.href = '/';
            }
        },
        error: function (request, status, error) {
            console.log(request.responseText);
        }
    });
}, registerEvents = function(){
    $(".date-buttons button").on('click', function(e){
        updateTable(e.currentTarget.getAttribute('data-attribute-id'));
        $(".date-buttons button:disabled")[0].removeAttribute('disabled');
        e.currentTarget.setAttribute('disabled', 'disabled');
    });

    $(document).on('pjax:complete', function() {
        registerPjaxEvents();
    });

    registerPjaxEvents();
}, registerPjaxEvents = function(){
    $(".view-order-btn").on('click', function(e){
        showSaleDetails(e);
    });

    $(".view-invoice-btn").on('click', function(e){
        e.preventDefault();
        window.open('/printinvoice/' + e.currentTarget.getAttribute("data-attribute-id"), '', 'scrollbars=1');
    });

    $(".edit-order-btn").on("click", function(e){
        //editOrder(e);
        swal("Проблемы с кэшированием - устраним, и сразу же запустим");
    });
};

registerEvents();
JS;

\rmrevin\yii\fontawesome\cdn\AssetBundle::register($this);

$this->registerJs($js);
?>
<div class="header">
    <div class="content">
        <div class="left">
            <a class="btn btn-default btn-lg" href="/">Назад</a>
        </div>
        <div class="title">
            <h1><?=$this->title?></h1>
        </div>
    </div>
</div>
<div class="content main-small">
    <div class="btn-group date-buttons">
        <button disabled data-attribute="today" class="btn btn-default">Сегодня</button>
        <button data-attribute="yesterday" class="btn btn-default">Вчера</button>
        <button data-attribute="week" class="btn btn-default">Неделя</button>
        <button data-attribute="month" class="btn btn-default">Месяц</button>
    </div>
    <br>
    <br>
    <?=\kartik\grid\GridView::widget([
        'dataProvider'  =>  $salesProvider,
        'id'            =>  'salesTable',
        'summary'       =>  false,
        //'perfectScrollbar'  =>  true,
        'hover'         =>  true,
        'striped'       =>  false,
        'pjax'          =>  true,
        'rowOptions'    =>  [
            'style'     =>  'cursor: pointer'
        ],
        'showPageSummary'=>  true,
        'resizableColumns'  =>  false,
        'columns'       =>  [
            [
                'class' =>  \kartik\grid\SerialColumn::className(),
                'width'     =>  '40px',
            ],
            [
                'hAlign'    =>  'center',
                'attribute' =>  'customerID',
                'value'     =>  function($model) use(&$customers){
                    if($model->customerID != 0){
                        return $customers[$model->customerID]->Company;
                    }
                }
            ],
            [
                'hAlign'    =>  'center',
                'width'     =>  '140px',
                'attribute' =>  'responsibleUser',
                'value'     =>  function($model){
                    if($model->responsibleUser != 0){
                        return \common\models\Siteuser::getUser($model->responsibleUser)->name;
                    }

                    return ;
                }
            ],
            [
                'hAlign'    =>  'center',
                'attribute' =>  'doneTime',
                'width'     =>  '120px',
                'value'     =>  function($model){
                    return \Yii::$app->formatter->asDatetime($model->doneTime, 'php:d.m H:i');
                }
            ],
            [
                'hAlign'    =>  'center',
                'header'    =>  'Колл-во товаров',
                'width'     =>  '100px',
                'pageSummary'=> true,
                'value'     =>  function($model){
                    return $model->createdOrderItemsCount;
                }
            ],
            [
                'hAlign'    =>  'center',
                'header'    =>  'Сумма',
                'width'     =>  '120px',
                'pageSummary'=> true,
                'value'     =>  function($model){
                    return $model->createdOrderSum.' грн.';
                }
            ],
            [
                'hAlign'    =>  'center',
                'width'     =>  '150px',
                //'format'    =>  'raw',
                /*'value'     =>  function($model){

                    return Html::a('Накладная', \yii\helpers\Url::toRoute('/printinvoice/'.$model->createdOrder), ['style' => 'z-index: 1000', 'data-pjax' => 0, 'class' => 'invoiceOrder', 'data-attribute-id' => $model->createdOrder]);
                }*/
                'class'     =>  \kartik\grid\ActionColumn::className(),
                'buttons'   =>  [
                    'view'   =>  function($widget, $model){
                        return Html::button(\rmrevin\yii\fontawesome\FA::i('eye'), [
                            'class' =>  'btn btn-default btn-default-sm view-order-btn',
                            'title' =>  'Просмотреть содержимое',
                            'data-attribute-id' =>  $model->id
                        ]);
                    },
                    'invoice'   =>  function($widget, $model){
                        return Html::button(\rmrevin\yii\fontawesome\FA::i('file-text'), [
                            'class' =>  'btn btn-default btn-default-sm view-invoice-btn',
                            'title' =>  'Открыть накладную',
                            'data-attribute-id' =>  $model->createdOrder
                        ]);
                    },
                    'update'   =>  function($widget, $model){
                        return Html::button(\rmrevin\yii\fontawesome\FA::i('pencil'), [
                            'class' =>  'btn btn-default btn-default-sm edit-order-btn',
                            'title' =>  'Редактировать заказ',
                            'data-attribute-id' =>  $model->id
                        ]);
                    },
                    'delete'   =>  function($widget, $model){
                        return Html::button(\rmrevin\yii\fontawesome\FA::i('trash'), [
                            'class' =>  'btn btn-danger btn-default-sm delete-order-btn',
                            'title' =>  'Удалить заказ',
                            'data-attribute-id' =>  $model->id
                        ]);
                    },
                ],
                'template'  =>  Html::tag('div', '{view}{invoice}{update}{delete}', ['class' => 'btn-group btn-group-sm'])
            ]
        ],
    ])?>
</div>

<div class="footer">
    <div class="content">
        <div class="left">
            <a class="btn btn-default btn-lg" href="/checks">Чеки</a>
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

<?php
$saleDetailsModal = new \bobroid\remodal\Remodal([
    'id'                    =>  'saleDetails',
    'addRandomToID'         =>  false,
    'confirmButton'     =>  false,
    'cancelButton'      =>  false
]);

echo $saleDetailsModal->renderModal('<div class="load"></div>');
?>