<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="header">
    <div class="content">
        <div class="left">
            <a class="btn btn-default btn-lg" href="/">Назад</a>
        </div>
        <div class="title">
            <h1><?=$this->title?></h1>
        </div>
    </div>
</div>
<div class="content main-small">
    <div class="site-error">

        <h1><?= Html::encode($this->title) ?></h1>

        <div class="alert alert-danger">
            <?= nl2br(Html::encode($message)) ?>
        </div>

        <p>
            Возникла ошибка при обработке вашего запроса. Если код ошибки 404, возможно вы пытаетесь отктыть то, чего нет.
        </p>
        <p>
            Свяжитесь с техподдержкой, чтобы решить эту проблему.
        </p>

    </div>
</div>
