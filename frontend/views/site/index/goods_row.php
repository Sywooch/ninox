<?php
use bobroid\y2sp\ScrollPager;
use yii\bootstrap\Html;

\rmrevin\yii\fontawesome\cdn\AssetBundle::register($this);

echo Html::tag('div', \yii\widgets\ListView::widget([
    'dataProvider'	=>	$dataProvider,
    'itemView'	=>	function($model){
        return $this->render('good_card', ['good' => $model]);
    },
    'options'   =>  [
        'class' =>  'list-view listView-selector-'.\Yii::$app->request->get("type")
    ],
    'itemOptions'	=>	[
        'class'	=>	'goods-item item'
    ],
    'pager' =>  [
        'class'             =>  ScrollPager::className(),
        'container'         =>  '.listView-selector-'.\Yii::$app->request->get("type"),
        'item'              =>  'div.goods-item',
        'enabledExtensions' =>  [
            ScrollPager::EXTENSION_TRIGGER,
            ScrollPager::EXTENSION_SPINNER,
            ScrollPager::EXTENSION_PAGING,
        ],
        'triggerTemplate'   =>  Html::tag('div', Html::tag('span', \Yii::t('shop', 'СМОТРЕТЬ ВСЕ ТОВАРЫ')), ['class' => 'goods-item goods-item-style']),
        'spinnerTemplate'   =>  Html::tag('div', Html::tag('span', \rmrevin\yii\fontawesome\FA::i('refresh')->addCssClass('fa-spin')), ['class' => 'goods-item-style', 'style' => 'height: 345px; width: 215px; float: left; margin-left: 10px; margin-bottom: 20px; margin-right: 10px;']),
        'delay'    =>  0
    ],
    'summary'	=>	false
]), ['class' => 'goods-content-all items-grid']);