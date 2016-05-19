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

    <?=Slick::widget([
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
    ]).
    (sizeof($itemsNav) > 1 ? Slick::widget([
        'containerOptions' => [
            'id'    => 'modalSliderNav',
            'class' => 'second'
        ],
        'items' =>  $itemsNav,
        'clientOptions' => [
            'arrows'         => true,
            'focusOnSelect'  => true,
            'infinite'       => true,
            'slidesToShow'   => 8,
            'slidesToScroll' => 1,
            'asNavFor'       => '#modalSliderFor',
            'cssEase'        => 'linear',
        ]
    ]) : '')
?>

