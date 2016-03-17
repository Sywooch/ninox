<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 16.03.16
 * Time: 17:32
 */

namespace frontend\helpers;


use frontend\models\Banner;
use yii\base\Component;
use yii\bootstrap\Html;

class BannerHelper extends Component
{

    /**
     * @param Banner[] $models
     *
     * @return array
     */
    public static function renderItems($models){
        $items = [];

        foreach($models as $model){
            $items[] = self::renderItem($model);
        }

        return $items;
    }

    /**
     * @param Banner $model
     *
     * @return string
     */
    public static function renderItem($model){
        switch($model->format){
            case $model::TYPE_IMAGE:
                return self::renderImageBanner($model);
                break;
            case $model::TYPE_HTML:
                return self::renderHTMLBanner($model);
                break;
        }
    }

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
    public static function renderImageBanner($banner){
        return Html::img('http://krasota-style.com.ua/'.$banner->banner_ru);
    }


}