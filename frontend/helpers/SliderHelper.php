<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 16.03.16
 * Time: 17:29
 */

namespace frontend\helpers;


use frontend\models\Banner;
use yii\bootstrap\Html;

class SliderHelper extends BannerHelper
{

    /**
     * @param Banner $banner
     */
    public static function renderHTMLBanner($banner){

    }

    /**
     * @param Banner $banner
     *
     * @return string
     */
    public static function renderImageBanner($banner, $withBlock = false){
        return Html::img('http://krasota-style.com.ua/'.$banner->banner_ru);
    }

}