<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $title;
?>
<div class="site-error">

    <h1>Ошибка: <?= Html::encode($this->title) ?></h1>

    <?= nl2br($message) ?>
</div>
