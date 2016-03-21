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

    /*const LAYOUT_2X2 = 'banner2x2';
    const LAYOUT_1X2 = 'banner1x2';
    const LAYOUT_1X1 = 'banner1x1';*/

    /**
     * @param Banner[] $models
     * @param bool $asArray
     *
     * @return array|string
     */
    public static function renderItems($models, $asArray = true){
        $items = [];

        foreach($models as $model){
            $items[] = self::renderItem($model);
        }

        return $asArray ? $items : implode('', $items);
    }

    /**
     * @param Banner $model
     *
     * @return string
     */
    public static function renderItem($model){
        switch($model->type){
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
        return Html::img('http://krasota-style.com.ua/'.$banner->banner->value);
    }


}