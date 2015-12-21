<?php
use backend\widgets\AddProblemWidget;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
?>
<div style="width: 90%; display: inline-block; float: left;">
    <span>Номер заказа <?=$model->orderNumber?></span>
    <br>
    <small style="display: block; opacity: 0.4; text-overflow: ellipsis; overflow: hidden; white-space: nowrap; max-width: 90%;"><?=$model->phone?></small>
    <span><?=$model->text?></span>
    <small style="display: block; opacity: 0.4; text-overflow: ellipsis; overflow: hidden; white-space: nowrap; max-width: 90%;"><?=$model->received?></small>
</div>
<div style="width: 9%; display: inline-block; padding: 5px;" class="btn-group-sm">
    <?=Html::button(FA::icon($model->read == 1 ? 'eye' : 'eye-slash'), [
        'class'                 =>  'ProblemState btn btn-default'.($model->read != 1 ? ' btn-danger' : ' btn-success'),
        'data-attribute-ProblemID' =>  $model->id
    ])
    /*Html::a(FA::icon('pencil'), '#updateProblem', [
        'class' =>  'problemEdit btn btn-default',
        'data-attribute-problemID' =>  $model->id,
    ])*/?>
    <?= AddProblemWidget::widget([
        'model'         =>  $model,
        'buttonLabel'   =>  '<i class="glyphicon glyphicon-pencil"></i>',
        'buttonClass'   =>  'btn btn-link'
    ])?>
</div>
<div style="clear: both"></div>



