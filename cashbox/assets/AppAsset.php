<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 12.01.16
 * Time: 14:03
 */

namespace cashbox\assets;


use rmrevin\yii\fontawesome\AssetBundle;

class AppAsset extends AssetBundle {

    public $js = [
        ''
    ];

    public $css = [
        'css/cashbox.css'
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
    ];

}