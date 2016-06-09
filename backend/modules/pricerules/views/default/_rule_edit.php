<?php

use yii\helpers\Html;

$css = <<<'CSS'
    .select2-container--open, .datepicker{
        z-index: 10000 !important;
    }
CSS;

$this->registerCss($css);

$form = new \kartik\form\ActiveForm([
    'options'   =>  [
        'class' =>  'ruleEditForm'
    ]
]);
$form->begin();

echo Html::hiddenInput('ruleID', 0).
Html::tag('ol', '', ['id' => 'ruleTermsList']);/*,
Html::button(FA::icon('plus').' Добавить', [
    'class' => 'ruleTermsList_add btn btn-success btn-sm',
    'style' =>  'margin: 0px auto; display: block;'
]);*/
$form->end();