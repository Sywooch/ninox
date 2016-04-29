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
    
    public $js = [
        'js/essentials.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
    ];

}