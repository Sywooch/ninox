<?php

use yii\bootstrap\Html;
$this->title = 'Отложенные чеки';

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
        'columns'       =>  [
            [
                'class' =>  \kartik\grid\SerialColumn::className()
            ],
            [
                'attribute' =>  'customerID'
            ],
            [
                'attribute' =>  'createdTime'
            ],
            [
                'attribute' =>  'responsibleUser'
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