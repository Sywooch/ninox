<?php

use common\helpers\Formatter;
use yii\helpers\Html;

$link = '/tovar/'.$model->link.'-g'.$model->ID;
$photo = \Yii::$app->params['cdn-link'].\Yii::$app->params['small-img-path'].$model->ico;
$flags = [];

$createFlag = function($name, $background){
	return Html::tag('div', $name, [
		'class' =>  'capt-flag '.$background
	]);
};

if($model->isNew){
    $flags[] = $createFlag(\Yii::t('shop', 'Новинка'), 'bg-capt-green');
}
if($model->originalGood){
    $flags[] = $createFlag(\Yii::t('shop', 'Оригинал'), 'bg-capt-purple');
}
if($model->discountType > 0 && $model->priceRuleID == 0){
    $flags[] = $createFlag(\Yii::t('shop', 'Распродажа'), 'bg-capt-red');
}

$flags = $flags ? Html::tag('div', implode('',$flags), ['class' => 'capt-flags']) : '';

$discountBlock = function($model){
	switch($model->discountType){
		case 1:
			$dimension = ' '.\Yii::$app->params['domainInfo']['currencyShortName'];
			break;
		case 2:
			$dimension = '%';
			break;
		default:
			$dimension = '';
			break;
	}

	return $model->priceRuleID ? Html::tag('div',
		Html::tag('div',
			Html::tag('div', $model->customerRule ? \Yii::t('shop', 'Опт') : \Yii::t('shop', 'Акция')).
			Html::tag('div', '-'.$model->discountSize.$dimension),
			['class' => 'top']).
		Html::tag('div',
			Html::tag('div', Formatter::getFormattedPrice($model->wholesale_price), ['class' => 'semi-bold']).
			Html::tag('div', \Yii::$app->params['domainInfo']['currencyShortName']),
			['class' =>  'bottom']
		),
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
				Formatter::getFormattedPrice($model->discountType > 0 && $model->priceRuleID == 0 ?
					$model->wholesale_real_price : $model->wholesale_real_price),
				[
					'class' => ($model->discountType > 0 && $model->priceRuleID == 0 ?
							'old-wholesale-price' : 'wholesale-price').' gray'
				]
			).
			(($model->wholesale_price != $model->retail_price || ($model->discountType > 0 && $model->priceRuleID == 0)) ?
				Html::tag('div',
					Formatter::getFormattedPrice($model->discountType > 0 && $model->priceRuleID == 0 ? $model->wholesale_price : $model->retail_real_price),
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

$countBlock = function($model){
	if($model->isUnlimited || $model->count > 9){
		$name = \Yii::t('shop', 'достаточно');
		$class = 'green';
	}else{
		if($model->count > 1){
			$name = \Yii::t('shop', 'заканчивается');
			$class = 'red';
		}else if($model->count <= 0){
			$name = \Yii::t('shop', 'нет в наличии');
			$class = 'gray';
		}else{
			$name = \Yii::t('shop', 'последний');
			$class = 'gray bold';
		}
	}

	return Html::tag('div',
		Html::tag('div',
			Html::tag('div', \Yii::t('shop', 'Остаток на складе:'), []).
			Html::tag('div', $name, ['class' => $class]),
			['class' => 'item-count-info']
		).
		\app\widgets\CartItemsCounterWidget::widget(['model' => $model]),
		['class' => 'item-counter-info']
	);
};

$onePriceBlock = function($model){
	return $model->priceForOneItem ?
		Html::tag('div',\Yii::t('shop', 'Цена за единицу:').
			Html::tag('span',
				$model->priceForOneItem.' ('.$model->num_opt.' '.$model->Measure1.'/'.\Yii::t('shop', 'уп').')',
				['class' => 'item-price-for-one']
			), ['class' => 'item-price-for-one-text']
		) : '';
};

$createStar = function($rate) use ($model){
	$options = [
		'class'         => 'icon-star'.($rate <= $model->rate && $model->rate < $rate + 1 ? ' current' : ''),
		'data-itemId'   => $model->ID,
		'data-rate'     => $rate,
		'content'       => $rate
	];
	switch($rate){
		case 5;
			$options = array_merge($options, [
				'itemprop' => 'bestRating',
				'title' => \Yii::t('shop', 'Отлично')
			]);
			break;
		case 4;
			$options = array_merge($options, [
				'title' => \Yii::t('shop', 'Хорошо')
			]);
			break;
		case 3;
			$options = array_merge($options, [
				'title' => \Yii::t('shop', 'Средне')
			]);
			break;
		case 2;
			$options = array_merge($options, [
				'title' => \Yii::t('shop', 'Приемлемо')
			]);
			break;
		case 1;
			$options = array_merge($options, [
				'itemprop' => 'worstRating',
				'title' => \Yii::t('shop', 'Плохо')
			]);
			break;
		default:
			break;

	}
	return Html::tag('span', '', $options);
};

$itemRating = function($model) use ($link, $createStar){
	return Html::tag('span',
		Html::tag('span',
			Html::tag('span',
				Yii::t('shop', '{n, plural, one{отзыв} few{отзыва} many{отзывов} other{отзывов}}',
					['n' =>  $model->reviewsCount]
				), ['class' => 'review-count-text blue']
			).
			Html::tag('span', $model->reviewsCount, [
				'class' => 'review-count icon-bubble blue',
				'itemprop' => 'reviewCount'
			]), [
			'class'     =>  'link-hide',
			'data-href' =>  $link.'#tab-comments'
		]).
		$createStar(5).
		$createStar(4).
		$createStar(3).
		$createStar(2).
		$createStar(1).
		Html::tag('span', $model->rate ? $model->rate : 5, [
			'class' => 'rate-count',
			'itemprop' => 'ratingValue'
		]), [
			'class' => 'rating',
			'itemscope' => '',
			'itemtype' => 'http://schema.org/AggregateRating'
		]);
};

$itemDopInfoBlock = function($model) use ($link, $itemRating){
	return Html::tag('div',
		(!empty($model->video) ?
			Html::tag('span', '', [
				'class'     =>  'icon-youtube link-hide',
				'data-href' =>  $link.'#tab-video'
			]) : ''
		).
		$itemRating($model),
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
		$countBlock($model).
		$onePriceBlock($model).
		$itemDopInfoBlock($model),
		['class' => 'inner-sub']),
	['class' => 'item']);