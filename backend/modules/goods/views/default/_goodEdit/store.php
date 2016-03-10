<?php
echo \kartik\grid\GridView::widget([
    'dataProvider'  =>  new \yii\data\ActiveDataProvider([
        'query' =>  \common\models\ShopGood::find()->where(['itemID' => $good->ID])
    ]),
    'emptyText' =>  'Нет остатков на складах',
    'summary'   =>  false,
    'bordered'  =>  false,
    'columns'   =>  [
        [
            'attribute' =>  'shopID',
            'header'    =>  'Магазин',
            'format'    =>  'html',
            'value'     =>  function($model){
                $shop = \common\models\Shop::findOne($model->shopID);

                if($shop){
                    return \yii\bootstrap\Html::a($shop->name, \yii\helpers\Url::to(['/store/show/'.$shop->id]));
                }

                return '(не найдено)';
            },
        ],[
            'attribute' =>  'count',
            'width'     =>  '200px',
            'label'     =>  'Колличество'
        ],
    ]

]);