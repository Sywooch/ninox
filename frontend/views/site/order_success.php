<?php

use backend\models\History;
use yii\web\View;

$order = History::find()->where(['ID' => $model->createdOrder])->one();

$products = [];

foreach($order->items as $item){
	$products[] = [
		'sku' => $item->good->ID,
		'name' => $item->good->Name,
		'category' => $item->good->category->name,
		'price' => $item->originalPrice,
		'quantity' => $item->originalCount
	];
}

$params = [
	'transactionId' => $order->number,
	'transactionAffiliation' => 'KrasotaStyle',
	'transactionTotal' => $order->orderSum,
	'transactionTax' => 0,
	'transactionShipping' => 0,
	'transactionProducts' => $products,
	'event' => 'trackTrans'
];

$eCommerce = 'dataLayer = [{';

foreach($params as $key => $param){
	switch($key){
		case 'transactionProducts':
			$eCommerce .= "'{$key}': [";
			foreach($param as $item){
				$eCommerce .= "{";
				foreach($item as $ikey => $iparam){
					$eCommerce .= "'{$ikey}': '{$iparam}',";
				}
				$eCommerce .= "},";
			}
			$eCommerce .= "],";
			break;
		default:
			$eCommerce .= "'{$key}': '{$param}',";
			break;
	}
}

$eCommerce .= '}];';

$this->registerJs($eCommerce, View::POS_BEGIN);

echo \Yii::t('shop', 'Ваш заказ оформлен! Спасибо!');