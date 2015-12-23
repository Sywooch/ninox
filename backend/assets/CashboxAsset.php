<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 22.12.15
 * Time: 16:10
 */

namespace backend\assets;


use kartik\base\AssetBundle;

class CashboxAsset extends AssetBundle{

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