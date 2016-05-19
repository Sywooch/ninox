<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 18.05.16
 * Time: 15:19
 */
use yii\helpers\Html;
use evgeniyrru\yii2slick\Slick;
?>

    <?=(!empty($itemsModal) ? Slick::widget([
        'containerOptions' => [
            'id'    => 'modalSliderFor',
            'class' => 'first'
        ],
        'items' =>  $itemsModal,
        'clientOptions' => [
            'arrows'         => true,
            'fade'           => false,
            'slidesToShow'   => 1,
            'slidesToScroll' => 1,
            'asNavFor'       => '#modalSliderNav',
        ]
    ]) : Html::img(\Yii::$app->params['cdn-link'].\Yii::$app->params['img-path'].$good->photo, [
        'itemprop' => 'image',
        'data-modal-index'  =>  0,
        'width' =>  '950px',
        'height'=>  '710px',
        'alt'   =>  $good->Name
    ])).
    (sizeof($itemsNav) > 1 ? Slick::widget([
        'containerOptions' => [
            'id'    => 'modalSliderNav',
            'class' => 'second'
        ],
        'items' =>  $itemsNav,
        'clientOptions' => [
            'arrows'         => false,
            'focusOnSelect'  => true,
            'infinite'       => true,
            'slidesToShow'   => 6,
            'slidesToScroll' => 1,
            'asNavFor'       => '#modalSliderFor',
            'cssEase'        => 'linear',
        ]
    ]) : '')
?>

