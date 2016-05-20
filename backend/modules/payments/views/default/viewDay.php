<?php

use kartik\grid\GridView;

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

if(\Yii::$app->request->get("type")){
    switch(\Yii::$app->request->get("type")){
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
            'width'     =>  '40px'
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
            'hAlign'    =>  GridView::ALIGN_CENTER,
            'vAlign'    =>  GridView::ALIGN_MIDDLE,
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
            'value'     =>  function($model){
                if(empty($model->customerPhone)){
                    return '';
                }

                return \Yii::$app->formatter->asPhone($model->customerPhone);
            }
        ],
    ]
]);