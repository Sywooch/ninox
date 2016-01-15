<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 15.01.16
 * Time: 16:38
 */

namespace backend\assets;


use yii\web\AssetBundle;

class InputAreaAsset extends AssetBundle{

    public $js = [
        '/js/jquery.add-input-area.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

}