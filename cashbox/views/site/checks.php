<?php

use kartik\grid\GridView;
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
            url:    '/loadpostpone',
            data: {
                postponeOrderID: orderID
            },
            type:   'post',
            success: function(){
                location.href = '/';
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
            <a class="btn btn-default btn-lg" href="/">Назад</a>
        </div>
        <div class="title">
            <h1><?=$this->title?></h1>
        </div>
    </div>
</div>
<div class="content main-small">
    <?=GridView::widget([
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
                'attribute' =>  'createdTime',
                'width'     =>  '130px',
                'format'    =>  'raw',
                'hAlign'    =>  GridView::ALIGN_CENTER,
                'vAlign'    =>  GridView::ALIGN_MIDDLE,
                'value'     =>  function($model){
                    return  Html::tag('div', \Yii::$app->formatter->asDatetime($model->createdTime, 'dd MMMM YYYY').' г.').
                    Html::tag('div', \Yii::$app->formatter->asDatetime($model->createdTime, 'HH:mm'));
                }
            ],
            [
                'attribute' =>  'customerID',
                'value'     =>  function($model){
                    if(!empty($model->customer)){
                        return $model->customer->Company;
                    }

                    return;
                }
            ],
            [
                'attribute' =>  'responsibleUser',
                'value'     =>  function($model){
                    if(!empty($model->manager)){
                        return $model->manager->name;
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
            <a class="btn btn-default btn-lg" href="/sales">Продажи</a>
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