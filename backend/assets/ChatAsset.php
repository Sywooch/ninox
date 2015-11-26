<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 26.11.15
 * Time: 15:21
 */

namespace backend\assets;


use yii\web\AssetBundle;

class ChatAsset extends AssetBundle{

    public $js = [
        '/js/chat.js',
        'http://cdnjs.cloudflare.com/ajax/libs/list.js/1.1.1/list.min.js',
        'http://cdnjs.cloudflare.com/ajax/libs/handlebars.js/3.0.0/handlebars.min.js',
    ];

    public $css = [
        '/css/chat.reset.css',
        '/css/chat.style.css',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
        'rmrevin\yii\fontawesome\AssetBundle',
    ];

}