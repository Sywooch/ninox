<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 10/23/15
 * Time: 4:00 PM
 */

namespace common\helpers;


class Formatter{
	public static function getFormattedPrice($number, $sign = false, $currency = true){
		return ($sign && $number > 0 ? '+' : '').
		number_format($number * \Yii::$app->params['domainInfo']['currencyExchange'],
			\Yii::$app->params['domainInfo']['coins'] ? 2 : 0, ',', ' ').
		($currency ? ' '.\Yii::$app->params['domainInfo']['currencyShortName'] : '');
	}
} 