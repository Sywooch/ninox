<?php

use frontend\helpers\PriceRuleHelper;
use yii\helpers\Html;
use yii\widgets\ListView;

$helper = new PriceRuleHelper();

echo Html::tag('div', Html::tag('h2', \Yii::t('shop', 'Результаты поиска по запросу "{request}":', [
        'request' => \Yii::$app->request->get("string")
    ])).
    ListView::widget([
        'dataProvider'  =>  $goods,
        'summary'   =>  \Yii::t('shop', 'Найдено {totalCount} товаров'),
        'itemView'      =>  function($model, $param2, $param3, $widget) use (&$helper){
            $helper->recalc($model, true);

            return $this->render('_shop_good', [
                'model' =>  $model
            ]);
        },
        'itemOptions'   =>  [
            'class'     =>  'hovered'
        ],
        'pager'         =>  [
            'class' =>  \common\components\ShopPager::className()
        ]
]), [
    'class' =>  'content'
]);