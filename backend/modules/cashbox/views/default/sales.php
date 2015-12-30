<?php
use yii\bootstrap\Html;

$this->title = 'Продажи';

$js = <<<'SCRIPT'
var showSaleDetails = function(e){
    $.ajax({
        type: 'POST',
        url: '/cashbox/getsaledetails',
        data: {
            'orderID':  e.currentTarget.getAttribute('data-key')
        },
        success: function(data){
            $("[data-remodal-id=saleDetails] > div").replaceWith(data);
            $("[data-remodal-id=saleDetails]").remodal().open();
        },
        error: function (request, status, error) {
            console.log(request.responseText);
        }
    });
}

$("#salesTable table tbody tr").on('click', function(e){
    showSaleDetails(e);
});
SCRIPT;

$this->registerJs($js);
?>
<div class="header">
    <div class="content">
        <div class="left">
            <a class="btn btn-default btn-lg" href="/cashbox">Назад</a>
        </div>
        <div class="title">
            <h1><?=$this->title?></h1>
        </div>
    </div>
</div>
<div class="content main-small">
    <?=\kartik\grid\GridView::widget([
        'dataProvider'  =>  $salesProvider,
        'id'            =>  'salesTable',
        'summary'       =>  false,
        //'perfectScrollbar'  =>  true,
        'hover'         =>  true,
        'striped'       =>  false,
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
                'width'     =>  '100px',
                'format'    =>  'html',
                'value'     =>  function($model){
                    return Html::a('Накладная', \yii\helpers\Url::toRoute('/orders/printinvoice/'.$model->createdOrder));
                }
            ]
        ],
    ])?>
</div>

<div class="footer">
    <div class="content">
        <div class="left">
            <a class="btn btn-default btn-lg" href="/cashbox/checks">Чеки</a>
            <a class="btn btn-default btn-lg" href="/cashbox/returns">Возвраты</a>
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