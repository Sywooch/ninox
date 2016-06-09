<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 12.03.16
 * Time: 12:59
 */

namespace frontend\assets;


use yii\web\AssetBundle;

class MainMenuAsset extends AssetBundle
{
	public $sourcePath = '@web';
	public $js = [
		'js/mainMenu.js'
	];
}