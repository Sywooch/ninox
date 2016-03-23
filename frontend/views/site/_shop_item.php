<?php

use common\helpers\Formatter;
use yii\helpers\Html;

$link = '/tovar/'.$model->link.'-g'.$model->ID;
$photo = \Yii::$app->params['cdn-link'].\Yii::$app->params['small-img-path'].$model->ico;
$flags = [];

if($model->isNew){
    $flags[] = ['name' => \Yii::t('shop', 'Новинка'), 'options' => ['class' => 'icon-new']];
}
if($model->originalGood){
    $flags[] = ['name' => \Yii::t('shop', 'Оригинал'), 'options' => ['class' => 'icon-origin']];
}
if($model->discountType > 0 && $model->priceRuleID == 0){
    $flags[] = ['name' => \Yii::t('shop', 'Распродажа'), 'options' => ['class' => 'icon-sale']];
}

$flags = $flags ?
	Html::ul($flags, [
		'item' => function($item){
			return Html::tag('li',
				Html::tag('span', $item['name'], []),
				$item['options']);
		},
		'class' => 'item-labels'
	]) : '';

$discountBlock = function($model){
	return $model->priceRuleID ? Html::tag('div',
		Html::tag('div', $model->customerRule ? \Yii::t('shop', 'Опт') : \Yii::t('shop', 'Акция'), ['class' => 'top']).
		Html::tag('div', Formatter::getFormattedPrice($model->wholesalePrice, false, false), ['class' => 'middle']).
		Html::tag('div', \Yii::$app->params['domainInfo']['currencyShortName'], ['class' => 'bottom']),
		['class' => 'discount']
	) : '';
};

$buyBlock = function($model){
	$button = [
		'value'         =>  $model->count > 0 || $model->isUnlimited ?
			($model->inCart ?
				\Yii::t('shop', 'В корзине!') : \Yii::t('shop', 'Купить!')
			) : \Yii::t('shop', "Нет\r\nв наличии"),
		'class'         =>  'button '.($model->count > 0 || $model->isUnlimited ?
			($model->inCart ?
				'green-button open-cart' : 'yellow-button buy'
			) : 'gray-button out-of-stock').' small-button',
		'data-itemId'   =>  $model->ID,
		'data-count'    =>  '1',
	];
	return Html::tag('div',
		Html::tag('div',
			Html::tag('div',
				Formatter::getFormattedPrice($model->realWholesalePrice),
				[
					'class' => ($model->discountType > 0 && $model->priceRuleID == 0 ?
							'old-wholesale-price' : 'wholesale-price').' gray'
				]
			).
			(($model->wholesalePrice != $model->retailPrice || ($model->discountType > 0 && $model->priceRuleID == 0)) ?
				Html::tag('div',
					Formatter::getFormattedPrice($model->discountType > 0 && $model->priceRuleID == 0 ? $model->wholesalePrice : $model->realRetailPrice),
					[
						'class' => ($model->discountType > 0 && $model->priceRuleID == 0 ? 'wholesale-price red' : 'retail-price gray')
					]
				) : ''),
			['class' => 'price-list']
		).
		Html::tag('div',
			Html::input('button', null, $button['value'], $button),
			['class' => 'button-block']
		),
		['class' => 'price-block']
	);
};

$onePriceBlock = function($model){
	return $model->priceForOneItem ?
		Html::tag('div',\Yii::t('shop', 'Цена за единицу:').
			Html::tag('span',
				$model->priceForOneItem.' ('.$model->num_opt.' '.$model->measure.'/'.\Yii::t('shop', 'уп').')',
				['class' => 'item-price-for-one']
			), ['class' => 'item-price-for-one-text']
		) : '';
};

$itemDopInfoBlock = function($model) use ($link){
	return Html::tag('div',
		(!empty($model->video) ?
			Html::tag('span', '', [
				'class'     =>  'icon-youtube link-hide',
				'data-href' =>  $link.'#tab-video'
			]) : ''
		).
		$this->render('_shop_item/_shop_item_rate', ['model' => $model, 'link' => $link]),
		['class' => 'item-dop-info']);
};

echo Html::tag('div',
	Html::tag('div',
		Html::tag('div',
			Html::tag('span', '', [
				'class'         =>  'icon-heart'.
					(\Yii::$app->user->isGuest ? ' is-guest' : '').
					(\Yii::$app->user->isGuest ?
						'' : (\Yii::$app->user->identity->hasInWishlist($model->ID) ? ' green' : '')),
				'data-itemId'   =>  $model->ID
			]).
			Html::tag('span', \Yii::t('shop', 'в избранное'), ['class' => 'item-wish-text']).
			Html::tag('span', $model->Code, ['class' => 'item-code']),
			['class' => 'item-head']).
		Html::a(Html::img($photo,[
				'class' => 'item-img',
				'alt' => $model->Name,
				'height' => 180,
				'width' => 230
			]).
			Html::tag('div',
				Html::tag('span', \Yii::t('shop', 'Быстрый просмотр')),
				[
					'class' => 'icon-quick-view'
				]).
			$discountBlock($model).
			$flags.
			Html::tag('div', $model->Name, [
				'class' =>  'item-title '.($model->count > 0 || $model->isUnlimited ? 'blue' : 'gray'),
			]),
			$link,
			['title' => $model->Name.' - '.\Yii::t('shop', 'оптовый интернет-магазин Krasota-Style')]
		).
		$buyBlock($model),
		['class' => 'inner-main']
	).
	Html::tag('div',
		$this->render('_shop_item/_shop_item_counter', ['model' => $model]).
		$onePriceBlock($model).
		$itemDopInfoBlock($model),
		['class' => 'inner-sub']),
	['class' => 'item']);