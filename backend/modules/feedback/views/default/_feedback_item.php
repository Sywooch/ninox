<?php
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
?>
<div style="width: 90%; display: inline-block; float: left;">

    <span><?=$feedback->feedback?></span>
    <br>

</div>
<div style="width: 9%; display: inline-block; padding: 5px;" class="btn-group btn-group-sm">
    <?=Html::button(FA::icon('eye-slash'), [
        'class'                 =>  'FeedbackState btn btn-default btn-success',
        'data-attribute-feedbackID' =>  $feedback->id
    ]),
    Html::a(FA::icon('pencil'), '#updateFeedback', [
        'class' =>  'FeedbackEdit btn btn-default',
        'data-attribute-feedbackID' =>  $feedback->id,
    ])?>
</div>
<div style="clear: both"></div>