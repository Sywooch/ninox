<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 2/12/16
 * Time: 3:12 PM
 */

use app\widgets\CartItemsCounterWidget;
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
					'src'       =>  \Yii::$app->params['cdn-link'].\Yii::$app->params['small-img-path'].$model->ico,
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
				Html::tag('div',
					Html::tag('span',
						Formatter::getFormattedPrice($model->retailPrice),
						['class' => 'item-price-retail semi-bold']
					).
					Html::tag('span',
						Formatter::getFormattedPrice($model->wholesalePrice),
						['class' => 'item-price-wholesale semi-bold']
					).
					Html::tag('sup',
						$discount,
						['class' => 'item-price-discount'.($model->discountSize ? '' : ' disabled')]),
					['class' => 'item-prices'.($model->discountSize ? ' discounted' : '')]
				);
			}
		],
		[
			'format'        =>  'raw',
			'value'         =>  function($model){
				return CartItemsCounterWidget::widget(['model' => $model]);
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