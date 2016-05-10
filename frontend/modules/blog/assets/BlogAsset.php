<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 05.05.16
 * Time: 16:49
 */

namespace frontend\modules\blog\assets;


use yii\web\AssetBundle;

class BlogAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/blog.css'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}