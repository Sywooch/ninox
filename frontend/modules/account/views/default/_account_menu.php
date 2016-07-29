<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 28.07.16
 * Time: 15:34
 */

use yii\helpers\Html;
use yii\helpers\Url;

echo Html::tag('div',
	\frontend\widgets\ListGroupMenu::widget([
		'items'    => [
			[
				'label' =>  \Yii::t('shop', 'Мои заказы'),
				'href'  =>  Url::to(['/account/orders'])
			],
			[
				'label' =>  \Yii::t('shop', 'Личные данные'),
				'href'  =>  Url::to(['/account'])
			],
			[
				'label' =>  \Yii::t('shop', 'Моя скидка'),
				'href'  =>  Url::to(['/account/discount'])
			],
			[
				'label' =>  \Yii::t('shop', 'Список желаний'),
				'href'  =>  Url::to(['/account/wish-list'])
			],
			[
				'label' =>  \Yii::t('shop', 'Мои отзывы'),
				'href'  =>  Url::to(['/account/reviews'])
			],
		]
	]),
	[
		'class' =>  'menu'
	]
);