<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 25.03.16
 * Time: 12:23
 */

use yii\helpers\Html;

$labels = [];

if($model->isNew){
	$labels[] = ['name' => \Yii::t('shop', 'Новинка'), 'options' => ['class' => 'icon-new']];
}
if($model->originalGood){
	$labels[] = ['name' => \Yii::t('shop', 'Оригинал'), 'options' => ['class' => 'icon-origin']];
}
if($model->discountType > 0 && $model->priceRuleID == 0){
	$labels[] = ['name' => \Yii::t('shop', 'Распродажа'), 'options' => ['class' => 'icon-sale']];
}

echo $labels ?
	Html::ul($labels, [
		'item' => function($item){
			return Html::tag('li',
				Html::tag('span', $item['name']),
				$item['options']);
		},
		'class' => 'item-labels'
	]) : '';