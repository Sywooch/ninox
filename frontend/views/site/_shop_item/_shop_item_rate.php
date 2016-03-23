<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 23.03.16
 * Time: 15:40
 */

use yii\helpers\Html;

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

echo Html::tag('span',
	Html::tag('span',
		Html::tag('span', $model->reviewsCount, [
			'class' => 'reviews-count icon-bubble blue',
			'itemprop' => 'reviewCount'
		]).
		Html::tag('span',
			Yii::t('shop', '{n, plural, one{отзыв} few{отзыва} many{отзывов} other{отзывов}}',
				['n' =>  $model->reviewsCount]
			), ['class' => 'blue']
		), [
			'class'     =>  'link-hide reviews',
			'data-href' =>  $link.'#tab-comments'
		]
	).
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
	]
);