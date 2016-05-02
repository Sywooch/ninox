<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 25.03.16
 * Time: 12:30
 */

use common\helpers\Formatter;
use yii\helpers\Html;

echo $model->priceRuleID ? Html::tag('div',
	Html::tag('div', $model->customerRule ? \Yii::t('shop', 'Опт') : \Yii::t('shop', 'Акция'), ['class' => 'top']).
	Html::tag('div', Formatter::getFormattedPrice($model->wholesalePrice, false, false), ['class' => 'middle']).
	Html::tag('div', \Yii::$app->params['domainInfo']['currencyShortName'], ['class' => 'bottom']),
	['class' => 'discount']
) : '';