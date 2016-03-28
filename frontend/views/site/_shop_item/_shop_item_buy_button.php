<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 25.03.16
 * Time: 12:38
 */

use yii\helpers\Html;

switch($class){
	case 'small-button':
		$value = \Yii::t('shop', "Нет\r\nв наличии");
		break;
	default:
		$value = \Yii::t('shop', "Нет в наличии");
		break;
}

$button = [
	'value'         =>  $model->count > 0 || $model->isUnlimited ?
		($model->inCart ?
			\Yii::t('shop', 'В корзине!') : \Yii::t('shop', 'Купить!')
		) : $value,
	'class'         =>  'button '.($model->count > 0 || $model->isUnlimited ?
			($model->inCart ?
				'green-button open-cart ' : 'yellow-button buy '
			) : 'gray-button out-of-stock ').$class,
	'data-itemId'   =>  $model->ID,
	'data-count'    =>  '1',
];

echo Html::input('button', null, $button['value'], $button);