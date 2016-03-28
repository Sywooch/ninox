<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 26.03.16
 * Time: 15:56
 */

use yii\helpers\Html;

$color = empty($color) ? '' : ' '.$color;

echo Html::tag('span',
	Html::tag('span', '', ['class' => 'icon-heart']).
	Html::tag('span', (\Yii::$app->user->isGuest ?
		\Yii::t('shop', 'в избранное') :
		(\Yii::$app->user->identity->hasInWishlist($model->ID) ?
			\Yii::t('shop', 'в избранном') : \Yii::t('shop', 'в избранное'))),
		['class' => 'item-wish-text']
	),
	[
		'class' => 'item-wish'.
		(\Yii::$app->user->isGuest ? ' is-guest' : '').
		(\Yii::$app->user->isGuest ?
			$color : (\Yii::$app->user->identity->hasInWishlist($model->ID) ? ' green' : $color)),
		'data-itemId'   =>  $model->ID
	]);