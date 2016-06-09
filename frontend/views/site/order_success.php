<?php
use backend\models\History;
use yii\helpers\Html;
use yii\web\View;
use common\helpers\Formatter;

$this->title = 'Ваш заказ оформлен';
$this->params['breadcrumbs'][] = $this->title;

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

$delivery = $payment = '';

$paymentSum = empty($order->actualAmount) ? $order->realSum : $order->actualAmount;

$delivery = empty($order->deliveryType) ? 'Не выбрано' : $order->deliveryType;

switch($order->deliveryType){
	case 1:
		$delivery = Html::tag('span', 'Адресная доставка: ', ['class' => 'bold smalltext', 'style' => 'display: block;
		 float: left;']).
			Html::tag('span', $order->deliveryInfo);
		break;
	case 2:
		$delivery = Html::tag('span', "Склад № {$order->deliveryInfo}", ['class' => 'bold']);
		break;
	case 3:
		$delivery = Html::tag('span', "Самовывоз", ['class' => 'bold']);
		break;
}

switch($order->paymentType){
	case 1:
		$payment = 'Наложеный платёж';
		break;
	case 2:
		$payment = 'Оценочная стоимость';
		break;
	case 3:
		$payment = 'стоимость';
		break;
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


if(/*\Yii::$app->user->isGuest*/\Yii::$app->request->post("orderType") == 1){
	echo Html::tag('div',
		Html::tag('span', 'Спасибо за заказ!', ['class' => 'order-success-title']).
		Html::tag('div',
			Html::tag('div',
				Html::tag('span', '№ заказа: ' . $order->number . '').
				Html::tag('div', '', ['class' => 'blue-line']).
				Html::tag('span',
					'К оплате: ' . \Yii::t('shop', '{sum} {sign}', ['sum' => empty($order->actualAmount) ?
						$order->originalSum : $order->actualAmount, 'sign' =>
						\Yii::$app->params['domainInfo']['currencyShortName']]). '', ['class' => 'cart-sum']),
				['class' => 'order-info']).
			Html::tag('span',
				\Yii::t('shop', 'Мы свяжемся с Вами, в ближайшее время для подтверждения заказа.'),	[
					'class' => 'confirm'
				])), [
			'class' => 'content order-success'
		]);
}else{
	echo Html::tag('div',
		 Html::tag('span', 'Спасибо за заказ!', ['class' => 'order-success-title']).
		 Html::tag('div',
		 	Html::tag('div',
		 		Html::tag('span',
					Html::tag('span', '№ заказа:', ['class' => 'number-of-order']).
					Html::tag('b',  $order->number)).
		 		Html::tag('span',
		 			$order->customerName . ' ' . $order->customerSurname).
		 		Html::tag('span', \Yii::$app->formatter->asPhone(\Yii::$app->user->identity->phone)).
		 		Html::tag('span', $delivery).
		 		Html::tag('span', $payment).
		 		Html::tag('div', '', ['class' => 'blue-line']).
		 		Html::tag('span',
		 			'К оплате: ' . \Yii::t('shop', '{sum} {sign}', ['sum' => empty($order->actualAmount) ?
		 					$order->originalSum : $order->actualAmount, 'sign' =>
		 				\Yii::$app->params['domainInfo']['currencyShortName']]). '', ['class' => 'cart-sum']),
		 		['class' => 'order-info']).
		 	Html::tag('span',
		 		\Yii::t('shop', 'Мы свяжемся с Вами, в ближайшее время для подтверждения заказа.'),	[
		 			'class' => 'confirm'
		 		])), [
		 	'class' => 'content order-success'
		 ]);
	}
