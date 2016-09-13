<?php

use common\helpers\Formatter;
use yii\helpers\Html;
use yii\helpers\Url;

$link = Url::to(['/tovar/'.$model->link.'-g'.$model->ID]);
$photo = \Yii::$app->params['cdn-link'].\Yii::$app->params['small-img-path'].$model->photo;
$btnClass = isset($btnClass) ? $btnClass : 'small-button';
$innerSub = isset($innerSub) ? $innerSub : true;

$buyBlock = function($model, $btnClass){
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
		)/*.
		Html::tag('div',
			frontend\widgets\ItemBuyButtonWidget::widget(['model' => $model, 'btnClass' => $btnClass]),
			['class' => 'button-block']
		)*/,
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
		(!empty($model->videos) ?
			Html::tag('span', '', [
				'class'     =>  'icon-youtube link-hide',
				'data-href' =>  $link.'#tab-video'
			]) : ''
		).
		$this->render('_shop_item/_shop_item_rate', ['model' => $model, 'link' => $link]),
		['class' => 'item-dop-info']);
};

$linkBlock = function($model) use ($link, $photo){
	return Html::tag('div', Html::img($photo,[
		'alt' => $model->Name,
		'height' => 180,
		'width' => 230,
		'onerror' => "this.src='".\Yii::$app->params['noimage']."';"
	]), [
		'class' => 'item-img',
	]).
	$this->render('_shop_item/_shop_item_discount', ['model' => $model]).
	$this->render('_shop_item/_shop_item_labels', ['model' => $model]).
	Html::tag('div',
		Html::tag('span', \Yii::t('shop', 'Быстрый просмотр')),
		[
			'class' => 'icon-quick-view'
		]).
	Html::tag('div', $model->Name, [
		'class' =>  'item-title blue',
	]);
};

echo Html::tag('div',
	Html::tag('div',
		/*Html::tag('div',
			$this->render('_shop_item/_shop_item_wish', ['model' => $model]).
			Html::tag('span', $model->Code, ['class' => 'item-code']),
			['class' => 'item-head clear-fix']
		).*/
		($model->enabled && ($model->count > 0 || $model->isUnlimited) ?
				Html::a($linkBlock($model),
					$link,
					['title' => $model->Name.' - '.\Yii::t('shop', 'оптовый интернет-магазин Krasota-Style')]
				) : $linkBlock($model).Html::tag('div', \Yii::t('shop', 'Нет в наличии'), ['class' => 'out-of-store-message'])
		).
		$buyBlock($model, $btnClass),
		['class' => 'inner-main']
	).
	($innerSub ? Html::tag('div',
		$this->render('_shop_item/_shop_item_counter', ['model' => $model]).
		$onePriceBlock($model).
		$itemDopInfoBlock($model),
		['class' => 'inner-sub']) : ''
	),
	['class' => 'item'.($model->enabled && ($model->count > 0 || $model->isUnlimited) ? '' : ' out-of-store')]
);