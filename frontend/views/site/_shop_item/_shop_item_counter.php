<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 23.03.16
 * Time: 17:01
 */

use app\widgets\CartItemsCounterWidget;
use yii\helpers\Html;

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

echo Html::tag('div',
	Html::tag('div',
		Html::tag('div', \Yii::t('shop', 'Остаток на складе:'), []).
		Html::tag('div', $name, ['class' => $class]),
		['class' => 'item-count-info']
	).
	CartItemsCounterWidget::widget(['model' => $model]),
	['class' => 'item-counter-info']
);