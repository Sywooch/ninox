<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 10/18/15
 * Time: 4:06 PM
 */

use yii\helpers\Html;

echo Html::tag('div',
	Html::tag('div', '', [
		'class'         =>  'minus',
		'data-itemId'   =>  $itemID,
		'data-count'    =>  -1,
	]).
	Html::tag('input', '', [
		'value'         =>  $value,
		'readonly'      =>  'readonly',
		'name'          =>  'count',
		'class'         =>  'count',
		'type'          =>  'text',
		'data-itemId'   =>  $itemID,
		'data-store'    =>  $count

	]).
	Html::tag('div', '', [
		'class'         =>  'plus',
		'data-itemId'   =>  $itemID,
		'data-count'    =>  1,
	]),
	[
		'class' => 'counter'
	]);