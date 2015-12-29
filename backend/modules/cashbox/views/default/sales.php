<?php
use yii\bootstrap\Html;

$this->title = 'Продажи';

$js = <<<'SCRIPT'
var showSaleDetails = function(e){
    $.ajax({
        type: 'POST',
        url: '/cashbox/getsaledetails',
        success: function(data){
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
        'columns'       =>  [
            [
                'class' =>  \yii\grid\SerialColumn::className()
            ],
            [
                'attribute' =>  'customerID',
                'value'     =>  function($model) use(&$customers){
                    if($model->customerID != 0){
                        return $customers[$model->customerID]->Company;
                    }
                }
            ],
            [
                'attribute' =>  'responsibleUser',
                'value'     =>  function($model){
                    if($model->responsibleUser != 0){
                        return \common\models\Siteuser::getUser($model->responsibleUser)->name;
                    }

                    return ;
                }
            ],
            [
                'attribute' =>  'doneTime',
                'value'     =>  function($model){
                    return \Yii::$app->formatter->asDatetime($model->doneTime, 'php:d.m H:i');
                }
            ],
            [
                'header'    =>  'Сумма',
                'value'     =>  function($model){
                    return $model->createdOrderSum;
                }
            ],
            [
                'header'    =>  'Колл-во товаров',
                'value'     =>  function($model){
                    return $model->createdOrderItemsCount;
                }
            ],
            [
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

echo $saleDetailsModal->renderModal('Under development...');
?>