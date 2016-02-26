<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 2/12/16
 * Time: 3:12 PM
 */

use common\helpers\Formatter;
use frontend\helpers\PriceRuleHelper;
use yii\helpers\Html;

$w = new \app\widgets\CartItemsCounterWidget();

echo \kartik\grid\GridView::widget([
	'dataProvider'  =>  new \yii\data\ArrayDataProvider([
		'models'     =>  \Yii::$app->cart->itemsCount ? \Yii::$app->cart->goods : []
	]),
	'id'            =>  'cart-gridview',
	'emptyText'     =>  '',
	'showHeader'    =>  false,
	'summary'       =>  false,
	'pjax'          =>  true,
	'bordered'      =>  false,
	'striped'       =>  false,
	'columns'       =>  [
		[
			'format'        =>  'raw',
			'value'         =>  function($model){
					return Html::tag('div', '', [
						'class'         =>  'remove-item',
						'data-itemId'   =>  $model->ID,
						'data-count'    =>  0,
					]).
					Html::tag('img', '', [
						'src'       =>  \Yii::$app->params['cdn-link'].'/img/catalog/sm/'.$model->ico,
						'alt'       =>  $model->Name.' '.\Yii::t('shop', 'от интернет магазина Krasota-Style.ua'),
						'width'     =>  '100px',
						'height'    =>  '75px'
					]);
				}
		],
		[
			'format'        =>  'html',
			'value'         =>  function($model){
					return Html::tag('div', $model->Name, ['class'  =>  'item-name blue']).
					Html::tag('div',
						Html::tag('span', Formatter::getFormattedPrice($model->retail_price).' '.\Yii::$app->params['domainInfo']['currencyShortName'], ['class'   =>  'item-price-retail semi-bold']).
						Html::tag('span', Formatter::getFormattedPrice($model->wholesale_price).' '.\Yii::$app->params['domainInfo']['currencyShortName'], ['class'   =>  'item-price-wholesale semi-bold']).
						Html::tag('sup', '-'.$model->discountSize.($model->discountType == 1 ? \Yii::$app->params['domainInfo']['currencyShortName'] : ($model->discountType == 2 ? '%' : '')), ['class'   =>  'item-price-discount'.($model->discountSize ? '' : ' disabled')]), [
							'class'   =>  'item-prices'.($model->discountSize ? ' discounted' : '')
						]);
				}
		],
		[
			'class'         =>  \yii\grid\ActionColumn::className(),
			'buttons'       =>  [
				'plus'  =>  function($url, $model, $key) use(&$w){
						$w->setOptions([
							'itemID'    =>  $model->ID,
							'value'     =>  $model->inCart ? $model->inCart : 1,
							'store'     =>  $model->isUnlimited ? 1000 : $model->count,
							'inCart'    =>  $model->inCart,
						]);
						return $w->renderPlus();
					},
				'minus'  =>  function($url, $model, $key) use(&$w){
						$w->setOptions([
							'itemID'    =>  $model->ID,
							'value'     =>  $model->inCart ? $model->inCart : 1,
							'store'     =>  $model->isUnlimited ? 1000 : $model->count,
							'inCart'    =>  $model->inCart,
						]);
						return $w->renderMinus();
					},
				'counter'  =>  function($url, $model, $key) use(&$w){
						$w->setOptions([
							'itemID'    =>  $model->ID,
							'value'     =>  $model->inCart ? $model->inCart : 1,
							'store'     =>  $model->isUnlimited ? 1000 : $model->count,
							'inCart'    =>  $model->inCart,
						]);

						return $w->renderInput();
					},
			],
			'template'      =>  Html::tag('div', '{minus}{counter}{plus}', ['class' => 'counter']),
			'contentOptions'       =>  [
				'data-col-seq'  =>  2
			]

		],
		[
			'format'        =>  'html',
			'value'         =>  function($model){
					return Html::tag('div', Formatter::getFormattedPrice((\Yii::$app->cart->wholesale ? $model->wholesale_price : $model->retail_price) * $model->inCart).' '.\Yii::$app->params['domainInfo']['currencyShortName'], [
						'class' =>  'item-price-amount'
					]);
				}
		]
	],
]);