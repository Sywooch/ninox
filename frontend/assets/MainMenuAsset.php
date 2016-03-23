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

    public $publishOptions = [
        'forceCopy' => true
    ];

    public $js = [
        'js/mainMenu.js'
    ];

    public $css = [
        'css/menu.css'
    ];

}