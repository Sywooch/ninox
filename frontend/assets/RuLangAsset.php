<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 9/4/15
 * Time: 5:10 PM
 */

namespace app\assets;

use yii\web\AssetBundle;

class RuLangAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/translations/ru_RU.css'
	];
	public $js = [
	];
	public $depends = [
		'app\assets\FrontEndAsset'
	];
}