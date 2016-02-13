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
		'models'     =>  \Yii::$app->cart->goods
	]),
	'id'            =>  'cart-gridview',
	'emptyText'     =>  \Yii::t('shop', 'Ваша корзина пуста!'),
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
					]);
				},
			'contentOptions'       =>  [
				'valign'    =>  'top'
			]
		],
		[
			'format'        =>  'html',
			'value'         =>  function($model){
					return Html::tag('img', '', [
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
					Html::tag('div', \Yii::t('shop', 'Код').': '.$model->Code, ['class'   =>  'item-code']).
					Html::tag('div', $model->wholesale_price, ['class'   =>  'item-price']).
					Html::tag('div', $model->retail_price, ['class'   =>  'item-price']);
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
			'template'      =>  Html::tag('div', '{minus}{counter}{plus}', ['class' => 'counter'])

		],
		[
			'format'        =>  'html',
			'contentOptions'    =>  [
				'class'         =>  'sums',
			],
			'value'         =>  function($model){
					return Html::tag('div', Formatter::getFormattedPrice($model->retail_real_price * $model->inCart).' '.\Yii::$app->params['domainInfo']['currencyShortName'], [
						'class' =>  'old-sum semi-bold blue'.($model->discountType == 0 ? ' disabled' : ''),
					]).
					Html::tag('div', Formatter::getFormattedPrice($model->retail_price * $model->inCart).' '.\Yii::$app->params['domainInfo']['currencyShortName'], [
						'class' =>  'current-sum blue'
					]);
				}
		]
	],
]);

?>