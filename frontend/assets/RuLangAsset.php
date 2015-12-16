<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 9/4/15
 * Time: 5:10 PM
 */

namespace frontend\assets;

use yii\web\AssetBundle;

class RuLangAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/translations/ru_RU.css',
	];
	public $js = [
		'js/translations/ru_RU.js',
	];
	public $depends = [
		'frontend\assets\FrontEndAsset',
	];
}