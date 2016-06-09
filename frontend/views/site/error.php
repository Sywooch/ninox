<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;

echo Html::tag('div',
    Html::img('/img/site/404.png',
        ['style' => 'display: block; margin: 0 auto;']
    ),
    ['class' => 'content']);