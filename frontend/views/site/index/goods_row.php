<?php
use bobroid\y2sp\ScrollPager;
use frontend\helpers\PriceRuleHelper;
use yii\bootstrap\Html;

\rmrevin\yii\fontawesome\cdn\AssetBundle::register($this);

$helper = new PriceRuleHelper();

echo \yii\widgets\ListView::widget([
    'dataProvider'	=>	$dataProvider,
    'itemView'	    =>	function($model) use (&$helper){
        $helper->recalc($model, true);

        return $this->render('../_shop_item', [
            'model'     =>  $model,
            'btnClass'  =>  'mini-button',
            'innerSub'  =>  false
        ]);
    },
    'options'       =>  [
        'class' =>  'list-view listView-selector-'.\Yii::$app->request->get("type")
    ],
    'itemOptions'	=>	[
        'class'	=>	'hovered'
    ],
    'layout'        =>  Html::tag('div', '{items}', ['class' => 'items-grid clear-fix']).'{pager}',
    'pager'         =>  [
        'class'             =>  ScrollPager::className(),
        'container'         =>  '.list-view .items-grid',
        'item'              =>  '.hovered',
        'enabledExtensions' =>  [
            ScrollPager::EXTENSION_TRIGGER,
            ScrollPager::EXTENSION_SPINNER,
            ScrollPager::EXTENSION_PAGING,
        ],
        'triggerTemplate'   =>  Html::tag('div', Html::tag('span', \Yii::t('shop', 'СМОТРЕТЬ ВСЕ ТОВАРЫ')), ['class' => 'ias-trigger']),
        'spinnerTemplate'   =>  Html::tag('div', Html::tag('span', \rmrevin\yii\fontawesome\FA::i('refresh')->addCssClass('fa-spin')), ['class' => 'goods-item-style', 'style' => 'height: 345px; width: 215px; float: left; margin-left: 10px; margin-bottom: 20px; margin-right: 10px;']),
        'delay'             =>  0
    ],
    'summary'       =>  false
]);