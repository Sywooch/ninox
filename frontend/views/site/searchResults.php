<?php

$this->title = \Yii::t('shop', 'Результаты поиска по запросу "{request}"', [
    'request'   =>  \Yii::$app->request->get("string")
]);

$this->params['breadcrumbs'][] = [
    'label' =>  \Yii::t('shop', 'Результаты поиска')
];

use frontend\helpers\PriceRuleHelper;
use yii\helpers\Html;
use yii\widgets\ListView;

$helper = new PriceRuleHelper();

echo Html::tag('div', \yii\widgets\Breadcrumbs::widget([
        'activeItemTemplate'    =>  '<span class="item-name" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">{link}</span>',
        'itemTemplate'          =>  '
                    <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">{link}</span>
                    <span class="fa fa-long-arrow-right fa-fw"></span>
                ',
        'links'                 =>  $this->params['breadcrumbs']
    ]).Html::tag('h2', \Yii::t('shop', 'Результаты поиска по запросу "{request}":', [
        'request' => \Yii::$app->request->get("string")
    ])).
    ListView::widget([
        'dataProvider'  =>  $goods,
        'summary'       =>  Html::tag('div', \Yii::t('shop', 'Найдено {totalCount} товаров'), ['class' => 'summary']),
        'itemView'      =>  function($model, $param2, $param3, $widget) use (&$helper){
            $helper->recalc($model, true);

            return $this->render('_shop_item', [
                'model' =>  $model
            ]);
        },
        'layout' => '{summary}'.
            Html::tag('div', '{items}', ['class' => 'items-grid']).
            '{pager}',
        'itemOptions'   =>  [
            'class'     =>  'hovered'
        ],
        'pager'         =>  [
            'class' =>  \common\components\ShopPager::className()
        ]
]), [
    'class' =>  'content catalog',
    'style' =>  'margin-top: 20px;'
]);