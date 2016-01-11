<?php

use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\Html;
$this->title = 'Отложенные чеки';

$js = <<<'JS'
var loadPostpone = function(orderID){
    swal({
        title: "Поднять чек",
        text: "Поднять отложеный чек №" + orderID + "?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Поднять!",
        cancelButtonText: "Отмена",
        closeOnConfirm: false
    },
    function(){
        $.ajax({
            url:    '/cashbox/loadpostpone',
            data: {
                postponeOrderID: orderID
            },
            type:   'post',
            success: function(){
                location.href = '/cashbox';
            }
        });
    });
};

$("#cashboxGrid tr").on('click', function(e){
    loadPostpone(e.currentTarget.getAttribute('data-key'));
});
JS;

$this->registerJs($js);

rmrevin\yii\fontawesome\AssetBundle::register($this);
\bobroid\sweetalert\SweetalertAsset::register($this);
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
        'dataProvider'  =>  $checksItems,
        'id'            =>  'cashboxGrid',
        'summary'       =>  false,
        'emptyText'     =>  false,
        'resizableColumns'=>false,
        'hover'         =>false,
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
                'class' =>  \kartik\grid\SerialColumn::className(),
                'contentOptions'   =>  [
                    'class' =>  'counter',
                    'width' =>  '40px;'
                ],
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
                'attribute' =>  'createdTime'
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
                'value'     =>  function($model){
                    return '';
                }
            ]
        ]
    ])?>
</div>
<div class="footer">
    <div class="content">
        <div class="left">
            <a class="btn btn-default btn-lg" href="/cashbox/sales">Продажи</a>
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