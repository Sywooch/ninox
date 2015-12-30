<?php

use yii\bootstrap\Html;
$this->title = 'Возвраты';

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
        'dataProvider'  =>  $returns,
    ])?>
</div>
<div class="footer">
    <div class="content">
        <div class="left">
            <a class="btn btn-default btn-lg" href="/cashbox/sales">Продажи</a>
            <a class="btn btn-default btn-lg" href="/cashbox/checks">Возвраты</a>
        </div>
        <div class="right">
            <?=Html::button((\Yii::$app->request->cookies->getValue("cashboxPriceType", 0) == 1 ? 'Опт' : 'Розница'), [
                'class' =>  'btn btn-lg btn-'.(\Yii::$app->request->cookies->getValue("cashboxPriceType", 0) == 0 ? 'danger' : 'success'),
                'id'    =>  'changeCashboxType',
            ])?>
        </div>
    </div>
</div>