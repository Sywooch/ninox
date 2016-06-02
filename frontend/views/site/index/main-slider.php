<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 10.03.16
 * Time: 11:57
 */

use evgeniyrru\yii2slick\Slick;
use yii\bootstrap\Html;

echo Html::beginTag('div', [
    'class' =>  'block-2x2-placeholder'
]);

if(!empty($items)){
    echo Slick::widget([
        'containerOptions' => [
            'id'    => 'sliderFor',
            'class' => 'first'
        ],
        'items' =>  $items,
        'clientOptions' => [
            'arrows'         => false,
            'dots'           => true,
            'fade'           => true,
            'slidesToShow'   => 1,
            'slidesToScroll' => 1,
            'asNavFor'       => '#sliderNav',
            'infinite'       => true,
        ]
    ]);
}

echo Html::endTag('div');
