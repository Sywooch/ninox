<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 18.01.16
 * Time: 17:14
 */

namespace backend\assets;


use yii\web\AssetBundle;

class CheckboxTreeAsset extends AssetBundle{

    public $js = [
        'js/fancytree/jquery.fancytree-all.min.js'
    ];
    public $css = [
        'js/fancytree/skin-win8/ui.fancytree.min.css'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset',
    ];

}