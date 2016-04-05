<?php

use common\helpers\Formatter;
use yii\helpers\Html;

$link = '/tovar/'.$model->link.'-g'.$model->ID;
$photo = \Yii::$app->params['cdn-link'].\Yii::$app->params['small-img-path'].$model->ico;

$buyBlock = function($model){
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
			$this->render('_shop_item/_shop_item_buy_button', ['model' => $model, 'class' => 'small-button']),
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
			$this->render('_shop_item/_shop_item_wish', ['model' => $model]).
			Html::tag('span', $model->Code, ['class' => 'item-code']),
			['class' => 'item-head clear-fix']).
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
			$this->render('_shop_item/_shop_item_discount', ['model' => $model]).
			$this->render('_shop_item/_shop_item_labels', ['model' => $model]).
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