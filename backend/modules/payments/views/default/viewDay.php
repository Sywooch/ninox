<?php

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

echo \kartik\grid\GridView::widget([
    'dataProvider'  =>  $dataProvider
]);