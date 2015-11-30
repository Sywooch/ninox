<?php
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
?>
<div style="width: 50%; display: inline-block; float: left;">
    <?=\backend\widgets\AddQuestionGroupWidget::widget([
        'model'         =>  $model,
        'buttonLabel'   =>  '<i class="glyphicon glyphicon-pencil"></i>',
        'buttonClass'   =>  'btn btn-link'
    ])?>
    <span><?=$model->name?></span>
    <br>
    <small style="display: block; opacity: 0.4; text-overflow: ellipsis; overflow: hidden; white-space: nowrap; max-width: 90%;"><?=$model->question?></small>
</div>
<div style="width: 9%; display: inline-block; padding: 5px;" class="btn-group btn-group-sm">
    <?=Html::button(FA::icon($model->published == 1 ? 'eye' : 'eye-slash'), [
        'class'                 =>  'QuestionState btn btn-default'.($model->published != 1 ? ' btn-danger' : ' btn-success'),
        'data-attribute-questionID' =>  $model->id


    ])
?>
</div>
<div style="clear: both"></div>

