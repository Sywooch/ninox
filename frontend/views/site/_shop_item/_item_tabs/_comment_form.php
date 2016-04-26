<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 22.04.16
 * Time: 17:13
 */


use frontend\models\CommentForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = new ActiveForm();

$model = new CommentForm();

$form::begin();
	echo $form->field($model, 'itemID')->hiddenInput(['value' => $itemID])->label(false).
	$form->field($model, 'parent')->hiddenInput(['value' => $parent])->label(false).
	$form->field($model, 'type')->hiddenInput(['value' => $type])->label(false).
	$form->field($model, 'comment')->textarea().
	$form->field($model, 'name').
	$form->field($model, 'email').
	Html::button(\Yii::t('site', 'Добавить'), [
		'type'	    =>	'submit',
		'class'	    =>	'button yellow-button small-button comment-button-submit'
	]).
	Html::button(\Yii::t('site', 'Отменить'), [
		'class'	    =>	'comment-button-cancel'
	]);
$form::end();