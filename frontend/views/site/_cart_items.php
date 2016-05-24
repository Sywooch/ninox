<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 2/12/16
 * Time: 3:12 PM
 */

use frontend\widgets\ItemCounterWidget;
use common\helpers\Formatter;
use yii\helpers\Html;

echo \kartik\grid\GridView::widget([
	'dataProvider'  =>  new \yii\data\ArrayDataProvider([
		'models'     =>  \Yii::$app->cart->itemsCount ? \Yii::$app->cart->goods : []
	]),
	'id'            =>  'cart-gridview',
	'emptyText'     =>  '',
	'showHeader'    =>  false,
	'summary'       =>  false,
	'pjax'          =>  true,
	'pjaxSettings'	=>	[
		'options'	=>	[
			'enablePushState'	=>	false,
			'enableReplaceState'=>	false
		]
	],
	'bordered'      =>  false,
	'striped'       =>  false,
	'export'		=>	false,
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
					'src'       =>  \Yii::$app->params['cdn-link'].\Yii::$app->params['small-img-path'].$model->photo,
					'alt'       =>  $model->Name.' '.\Yii::t('shop', 'от интернет магазина Krasota-Style.ua'),
					'width'     =>  '100px',
					'height'    =>  '75px'
				]);
			}
		],
		[
			'format'        =>  'html',
			'value'         =>  function($model){
				$discount = '-';
				switch($model->discountType){
					case 1:
						$discount .= Formatter::getFormattedPrice($model->discountSize);
						break;
					case 2:
						$discount .= $model->discountSize.'%';
						break;
					default:
						break;
				}
				return Html::tag('div', $model->Name, ['class' => 'item-name blue']).
				Html::tag('div', 'Код товара: '.$model->Code, ['class' => 'item-name']).
				Html::tag('div',
					Html::tag('span', $model->retailPrice == $model->wholesalePrice ?
						Formatter::getFormattedPrice($model->retailPrice) :
						\Yii::t('shop', 'розн. {price}',
							['price' => Formatter::getFormattedPrice($model->retailPrice)]
						),
						['class' => 'item-price-retail']
					).
					Html::tag('span', $model->retailPrice == $model->wholesalePrice ?
						Formatter::getFormattedPrice($model->wholesalePrice) :
						\Yii::t('shop', 'опт. {price}',
							['price' => Formatter::getFormattedPrice($model->wholesalePrice)]
						),
						['class' => 'item-price-wholesale']
					).
					Html::tag('span',
						$discount,
						['class' => 'item-price-discount'.($model->discountSize ? '' : ' disabled')]),
					['class' => 'item-prices'.($model->retailPrice == $model->wholesalePrice ? ' one-price' : '').
						($model->discountSize ? ' discounted' : '')]
				);
			}
		],
		[
			'format'        =>  'raw',
			'value'         =>  function($model){
				return ItemCounterWidget::widget(['model' => $model]);
			},
		],
		[
			'format'        =>  'html',
			'value'         =>  function($model){
				return Html::tag('div',
					Formatter::getFormattedPrice((\Yii::$app->cart->wholesale ?
							$model->wholesalePrice : $model->retailPrice) * $model->inCart),
					['class' => 'item-price-amount']
				);
			}
		]
	],
]);