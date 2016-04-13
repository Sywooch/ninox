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
	public $sourcePath = '@web';
	public $css = [
		'css/counter.css',
		'css/maskedphone.css',
		'css/outdatedbrowser.min.css',
		'css/site.css',
		'css/menu.css',
	];
	public $js = [
		'js/main.js',
		'js/jquery.sticky.js',
	];
	public $depends = [
		'yii\web\YiiAsset',
		'yii\web\JqueryAsset',
	];
}