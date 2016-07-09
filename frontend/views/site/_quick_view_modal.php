<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 04.07.16
 * Time: 17:31
 */

use evgeniyrru\yii2slick\Slick;
use yii\helpers\Html;

$items = [];
$itemsNav = [];

foreach($good->photos as $photo){
	$items[] = Html::img(\Yii::$app->params['cdn-link'].\Yii::$app->params['img-path'].$photo->ico, [
		'width'=>'475px',
		'height'=>'355px',
		'onerror' => "this.src='".\Yii::$app->params['noimage']."';"
	]);

	$itemsNav[] = Html::img(\Yii::$app->params['cdn-link'].\Yii::$app->params['small-img-path'].$photo->ico, [
		'width'=>'105px',
		'height'=>'80px',
		'onerror' => "this.src='".\Yii::$app->params['noimage']."';"
	]);
}

echo Html::tag('div',
	Html::tag('h1', $good->Name, ['class' => 'title']).
	Html::tag('div', \Yii::t('shop', 'Код:').$good->Code, ['class' => 'code blue']).
	(!empty($items) ? Slick::widget([
		'containerOptions' => [
			'id'    => 'sliderFor',
			'class' => 'first'
		],
		'items' =>  $items,
		'clientOptions' => [
			'arrows'         => false,
			'fade'           => true,
			'slidesToShow'   => 1,
			'slidesToScroll' => 1,
			'asNavFor'       => '',
		]
	]) : Html::img(\Yii::$app->params['cdn-link'].\Yii::$app->params['img-path'].$good->photo,
		[
			'itemprop' => 'image',
			'data-modal-index'  =>  0,
			'width' =>  '475px',
			'height'=>  '355px',
			'alt'   =>  $good->Name,
			'onerror' => "this.src='".\Yii::$app->params['noimage']."';"
		])
	).
	(sizeof($itemsNav) > 1 ? Slick::widget([
		'containerOptions' => [
			'id'    => 'sliderNav',
			'class' => 'second'
		],
		'items' =>  $itemsNav,
		'clientOptions' => [
			'arrows'         => false,
			'focusOnSelect'  => true,
			'infinite'       => true,
			'slidesToShow'   => 4,
			'slidesToScroll' => 1,
			'asNavFor'       => '#sliderFor',
			'cssEase'        => 'linear',
		]
	]) : ''),
	['class' => 'item-photos']
).
$this->render('_shop_item/_main_info', ['good' => $good]);