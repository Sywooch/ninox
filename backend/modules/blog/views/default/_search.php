<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ArticlesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="articles-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'content') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'keywords') ?>

    <?php // echo $form->field($model, 'ico') ?>

    <?php // echo $form->field($model, 'author') ?>

    <?php // echo $form->field($model, 'commentCount') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'link') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'show') ?>

    <?php // echo $form->field($model, 'publish') ?>

    <?php // echo $form->field($model, 'mod') ?>

    <?php // echo $form->field($model, 'rate') ?>

    <?php // echo $form->field($model, 'views') ?>

    <?php // echo $form->field($model, 'video') ?>

    <?php // echo $form->field($model, 'future_publish') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
