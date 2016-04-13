<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 05.04.16
 * Time: 16:08
 */

use yii\helpers\Html;
use yii2mod\slider\IonSlider;

var_dump($min);
var_dump($max);
var_dump($from);
var_dump($to);

echo Html::beginTag('div', ['class' => 'filters']).
	Html::tag('div',
		Html::tag('div', \Yii::t('shop', 'Цена'), ['class' => 'filter-head']).
		Html::tag('div',
			IonSlider::widget([
				'name'              =>  "slider",
				'type'              =>  "double",
				'class'             =>  "price-slider",
				'pluginOptions'     =>  [
					'min'       =>  $min,
					'max'       =>  $max,
					'from'      =>  $from,
					'to'        =>  $to,
					'step'      =>  \Yii::$app->params['domainInfo']['coins'] ? 0.01 : 1,
					'onChange'  =>  new \yii\web\JsExpression('
						function(data){
							$(".price-min").val(data.from);
							$(".price-max").val(data.to);
						}
					'),
					'onFinish'  =>  new \yii\web\JsExpression('
						function(data){
							updateFilter(data);
						}
					')
				]
			]),
			['class' => 'filter-rows']
		),
		['class' => 'filter-block']
	);
	foreach($filters as $filter){
		echo Html::tag('div',
			Html::tag('div', $filter['name'], ['class' => 'filter-head']).
			Html::checkboxList($filter['name'], $filter['checked'], $filter['options'], [
				'class' =>  'filter-rows',
				'item'  =>  function($index, $data, $name, $checked, $value){
					$options = array_merge(
						[
							'value'         =>  $value,
							'label'         =>  $data['label'].'('.$data['count'].')',
							'disabled'      =>  $data['count'] == 0
						],
						$data['count'] == 0 ? ['labelOptions' => ['class' => 'disabled']] : []
					);

					return Html::checkbox($name, $checked, $options);
				}
			]),
			['class' => 'filter-block']
		);
	}
echo Html::endTag('div');