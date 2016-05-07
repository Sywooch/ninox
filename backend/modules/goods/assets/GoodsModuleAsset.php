<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 28.04.16
 * Time: 17:05
 */

namespace backend\modules\goods\assets;


use yii\web\AssetBundle;

class GoodsModuleAsset extends AssetBundle
{

    public $sourcePath = '@modules/goods/assets';

    public $css = [
        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css'
    ];

    public $js = [
        'js/essentials.js',
        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
    ];

}