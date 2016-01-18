<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 10/23/15
 * Time: 4:00 PM
 */

namespace common\helpers;


class GoodHelper{
	public static function getPriceFormat($price){
		return number_format($price * \Yii::$app->params['domainInfo']['currencyExchange'], \Yii::$app->params['domainInfo']['coins'] ? 2 : 0, '.', ' ');
	}

	public static function getPriceInteger($price){
		return explode('.', number_format($price * \Yii::$app->params['domainInfo']['currencyExchange'], \Yii::$app->params['domainInfo']['coins'] ? 2 : 0, '.', ' '))[0];
	}

	public static function getPriceFraction($price){
		return explode('.', number_format($price * \Yii::$app->params['domainInfo']['currencyExchange'], \Yii::$app->params['domainInfo']['coins'] ? 2 : 0, '.', ' '))[1];
	}
} 