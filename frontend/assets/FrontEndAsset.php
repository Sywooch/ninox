<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 9/4/15
 * Time: 5:06 PM
 */

namespace frontend\assets;

use yii\web\AssetBundle;

class FrontEndAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/normalize.css',
		'css/ion.rangeSlider.css',
		'css/ion.rangeSlider.skinModern.css',
		'css/counter.css',
		'css/maskedphone.css',
		'css/outdatedbrowser.min.css',
		'css/site.css',
	];
	public $js = [
		'js/main.js',
	];
	public $depends = [
		'yii\web\YiiAsset',
		'yii\web\JqueryAsset',
	];
}