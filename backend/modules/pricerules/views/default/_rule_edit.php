<?php

use kartik\form\ActiveForm;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;

$css = <<<'CSS'
    .select2-container--open, .datepicker{
        z-index: 10000 !important;
    }
CSS;

$this->registerCss($css);

\backend\assets\InputAreaAsset::register($this);

$form = ActiveForm::begin([
    'options'   =>  [
        'class' =>  'ruleEditForm'
    ]
]);

echo Html::hiddenInput('ruleID', 0).
    Html::input('text', 'ruleName').
    Html::tag('ol', '', ['id' => 'ruleTermsList']),
    Html::button(FA::icon('plus').' Добавить', [
        'class' => 'ruleTermsList_add btn btn-success btn-sm',
        'style' =>  'margin: 0px auto; display: block;'
    ]).
    Html::tag('ol',
        Html::tag('li',
            'Скидка = '.Html::input('text', 'priceRuleActions[Discount]')).
        Html::tag('li',
            'Тип = '.Html::tag('select',
                Html::tag('option', 'грн.', ['value' => '1']).
                Html::tag('option', '%', ['value' => '2']).
                Html::tag('option', 'Назначить', ['value' => '3']),
                ['name' => 'priceRuleActions[Type]']
            )
        ),
        ['id' => 'ruleActionsList']
    );
$form->end();