<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 16.03.16
 * Time: 17:32
 */

namespace frontend\helpers;


use common\helpers\Formatter;
use frontend\models\Banner;
use frontend\models\Good;
use frontend\widgets\ItemBuyButtonWidget;
use yii\base\Component;
use yii\bootstrap\Html;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

class BannerHelper extends Component
{

    /**
     * @param Banner[] $models
     * @param bool $asArray
     *
     * @return array|string
     */
    public static function renderItems($models, $asArray = true, $withBlock = true){
        $items = [];

        foreach($models as $model){
            $items[] = self::renderItem($model, $withBlock);
        }

        return $asArray ? $items : implode('', $items);
    }

    /**
     * @param Banner $model
     * @param bool $withBlock
     *
     * @return string|null
     */
    public static function renderItem($model, $withBlock = false){
        if(empty($model)){
            return;
        }

        $banner = '';

        switch($model->type){
            case $model::TYPE_IMAGE:
                $banner = self::renderImageBanner($model, $withBlock);
                break;
            case $model::TYPE_HTML:
                $banner = self::renderHTMLBanner($model);
                break;
            case $model::TYPE_GOOD:
                $banner = self::renderGoodBanner($model);
                break;
            case $model::TYPE_GOOD_IMAGE:
                $banner = self::renderGoodImage($model);
                break;
        }

        return $banner;
    }

    /**
     * @param $banner Banner
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public static function renderGoodBanner($banner){
        $good = Good::findOne($banner->banner->value);

        if(!$good){
            throw new NotFoundHttpException("Товар с идентификатором {$banner->banner->value} не найден!");
        }

        $color = empty($color) ? '' : ' '.$color;//без цвета наверное будет

        (new PriceRuleHelper())->recalc($good, true);

        return Html::tag('div', Html::tag('div',
               // Html::tag('span', '', ['class' => 'icons-fav-bask']).
                Html::tag('div', html::tag('span', '',
                    ['class' => 'item-wish desire-ico ico'.
                        (\Yii::$app->user->isGuest ? ' is-guest' : '').
                        (\Yii::$app->user->isGuest ?
                        $color : (\Yii::$app->user->identity->hasInWishlist($good->ID) ? ' green' : $color)),
                        'data-itemId'   =>  $good->ID]).
                ItemBuyButtonWidget::widget(['model' => $good, 'btnClass' => 'micro-button']),
                    ['class' => 'icons-fav-bask']).
                Html::a(Html::tag('span', $good->Name), '/tovar/'.$good->link.'-g'.$good->ID).
                Html::tag('span', Formatter::getFormattedPrice($good->wholesalePrice), ['class' => 'price'])
            ).
            Html::tag('div', Html::a(Html::img('http://krasota-style.com.ua/img/catalog/'.$good->photo), '/tovar/'.$good->link.'-g'.$good->ID)), [
            'class' => 'goods-item'
        ]);
    }

    /**
     * @param $banner Banner
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public static function renderGoodImage($banner){
        $bannerValue = Json::decode($banner->banner->value);

        $good = Good::findOne($bannerValue['goodID']);

        if(!$good){
            throw new NotFoundHttpException("Товар с идентификатором {$bannerValue['goodID']} не найден!");
        }

        $color = empty($color) ? '' : ' '.$color;//без цвета наверное будет

        (new PriceRuleHelper())->recalc($good, true);

        return
            Html::tag('div',
                Html::a(Html::img('/img/site/'.$bannerValue['image']), '/tovar/'.$good->link.'-g'.$good->ID).

                    Html::tag('div', Html::tag('div', Formatter::getFormattedPrice($good->wholesalePrice), ['class'
                        => 'price']).
                        Html::tag('div',
                            Html::tag('span', '',
                                ['class' => 'item-wish desire-ico ico'.
                                    (\Yii::$app->user->isGuest ? ' is-guest' : '').
                                    (\Yii::$app->user->isGuest ?
                                        $color : (\Yii::$app->user->identity->hasInWishlist($good->ID) ? ' green' : $color)),
                                    'data-itemId'   =>  $good->ID]).
                            ItemBuyButtonWidget::widget(['model' => $good, 'btnClass' => 'micro-button']),
                             ['class' => 'banners-icons']),
                        ['class' => 'icons-fav-bask']),
                    [
                'class' => 'goods-item'
            ]);
    }

    /**
     * @param Banner $banner
     *
     * @return string
     */
    public static function renderHTMLBanner($banner){
        $content = $banner->banner->value;

        if(!empty($banner->banner->link)){
            $content = Html::a($content, $banner->banner->link);
        }

        return Html::tag('div', $content, [
            'class'	=>	'goods-item goods-item-style'
        ]);
    }

    /**
     * @param Banner $banner
     *
     * @return string
     */
    public static function renderImageBanner($banner, $withBlock){
        $content = Html::img(preg_match('/http/', $banner->banner->value) ? $banner->banner->value : \Yii::$app->params['frontend'].$banner->banner->value);
        
        $content = !empty($banner->banner->link) ? Html::a($content, $banner->banner->link) : $content;

        if($withBlock){
            return $content;
        }

        return Html::tag('div', $content, [
            'class'	=>	'goods-item banner-img'
        ]);
    }


}