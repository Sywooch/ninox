<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 28.09.15
 * Time: 17:27
 */

namespace common\components;


use yii\helpers\Html;
use yii\widgets\LinkPager;

class ShopPager extends LinkPager{

    protected function renderPageButton($label, $page, $class, $disabled, $active)
    {
        $options = ['class' => $class === '' ? null : $class];
        if ($active) {
            Html::addCssClass($options, $this->activePageCssClass);
            return Html::tag('li', Html::tag('span', $label), $options);
        }
        if ($disabled) {
            Html::addCssClass($options, $this->disabledPageCssClass);

            return Html::tag('li', Html::tag('span', $label), $options);
        }
        $linkOptions = $this->linkOptions;
        $linkOptions['data-page'] = $page;

        return Html::tag('li', Html::a($label, urldecode($this->pagination->createUrl($page)), $linkOptions), $options);
    }

}