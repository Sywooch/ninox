<?php
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
?>
<div style="width: 90%; display: inline-block; float: left;">
    <span><?=$question->name?></span>
    <br>
    <small style="display: block; opacity: 0.4; text-overflow: ellipsis; overflow: hidden; white-space: nowrap; max-width: 90%;"><?=$question->question?></small>
</div>
<div style="width: 9%; display: inline-block; padding: 5px;" class="btn-group btn-group-sm">
    <?=Html::button(FA::icon('eye-slash'), [
        'class'                 =>  'QuestionState btn btn-default btn-success',
        'data-attribute-questionID' =>  $question->id
    ]),
    Html::a(FA::icon('pencil'), '#updateQuestion', [
        'class' =>  'QuestionEdit btn btn-default',
        'data-attribute-questionID' =>  $question->id,
    ])?>
</div>
<div style="clear: both"></div>