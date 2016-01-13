<?php

use yii\bootstrap\Html;
$this->title = 'Возвраты';

$js = <<<'JS'
var updateTable = function(date){
    $.pjax({url: '/returns?smartfilter=' + date, container: '#returnsTable-pjax'});
};

$(".date-buttons button").on('click', function(e){
    updateTable(e.currentTarget.getAttribute('data-attribute'));
    $(".date-buttons button:disabled")[0].removeAttribute('disabled');
    e.currentTarget.setAttribute('disabled', 'disabled');
});
JS;

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
        'dataProvider'  =>  $returns,
        'id'            =>  'returnsTable',
        'summary'       =>  false,
        'pjax'          =>  true,
        /*'columns'       =>  [
            [
                'attribute' =>  'customerID'
            ],[
                'attribute' =>  'responsibleUser'
            ],[
                'attribute' =>  'doneTime'
            ],[

            ]
        ]*/
    ])?>
</div>
<div class="footer">
    <div class="content">
        <div class="left">
            <a class="btn btn-default btn-lg" href="/sales">Продажи</a>
            <a class="btn btn-default btn-lg" href="/checks">Чеки</a>
        </div>
        <div class="right">
            <?=Html::button((\Yii::$app->request->cookies->getValue("cashboxPriceType", 0) == 1 ? 'Опт' : 'Розница'), [
                'class' =>  'btn btn-lg btn-'.(\Yii::$app->request->cookies->getValue("cashboxPriceType", 0) == 0 ? 'danger' : 'success'),
                'id'    =>  'changeCashboxType',
            ])?>
        </div>
    </div>
</div>