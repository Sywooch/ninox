<?php
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
?>
<div style="width: 90%; display: inline-block; float: left;">
    <span><?=$rule->Name?></span>
    <br>
    <small style="display: block; opacity: 0.4; text-overflow: ellipsis; overflow: hidden; white-space: nowrap; max-width: 90%;"><?=$rule->Formula?></small>
</div>
<div style="width: 9%; display: inline-block; padding: 5px;" class="btn-group btn-group-sm">
    <?=Html::button(FA::icon($rule->Enabled == 1 ? 'eye' : 'eye-slash'), [
        'class'                 =>  'priceRuleState btn btn-default'.($rule->Enabled != 1 ? ' btn-danger' : ' btn-success'),
        'data-attribute-ruleID' =>  $rule->ID
    ]),
    Html::a(FA::icon('pencil'), '#updateRule', [
        'class' =>  'priceRuleEdit btn btn-default',
        'data-attribute-ruleID' =>  $rule->ID,
    ])?>
</div>
<div style="clear: both"></div>