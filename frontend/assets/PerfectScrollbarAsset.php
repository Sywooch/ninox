<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 12.03.16
 * Time: 12:53
 */

namespace frontend\assets;


use yii\web\AssetBundle;

class PerfectScrollbarAsset extends AssetBundle
{
    public $sourcePath = "@web";

    public $js = [
        'js/perfect-scrollbar.jquery.min.js'
    ];

    public $css = [
        'css/perfect-scrollbar.min.css'
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];

}