<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 05.04.16
 * Time: 16:08
 */

use yii\helpers\Html;

echo Html::beginTag('div', ['class' => 'filters']).
	Html::tag('div',
		Html::tag('div', \Yii::t('shop', 'Цена'), ['class' => 'filter-head']).
		Html::tag('div',
			Html::tag('div',
				Html::input('text', '', 0, ['id' => 'price-slider']).
				Html::tag('div',
					\Yii::t('shop', 'от').
					Html::input('text', '', '', ['id' => 'price-min']).
					\Yii::t('shop', 'до').
					Html::input('text', '', '', ['id' => 'price-max']).
					Html::input('button', '', 'OK', ['id' => 'price-update']),
					['class' => 'price-min-max']
				),
				['class' => 'filter-row']
			),
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
					return Html::checkbox($name, $checked, [
						'value'         =>  $value,
						'label'         =>  $data['label'].'('.$data['count'].')',
					]);
				}
			]),
			['class' => 'filter-block']
		);
	}
echo Html::endTag('div');