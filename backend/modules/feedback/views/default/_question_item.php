<?php
use backend\widgets\AddQuestionWidget;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
?><div>
<div class="" style="max-height: 100px; max-width: 100px; float: left; display: block; margin:10px " >
    <img src="<?=!empty($model->photo) ? $model->photo : '/img/noimage.png'?>" style="max-height: 100px; max-width: 100px;" alt="..." class="img-rounded">
</div>
<div style="width: 9%; display: block; padding: 5px; float: right"  class="pull-right">
    <?=Html::button(FA::icon($model->published == 1 ? 'eye' : 'eye-slash'), [
        'class'                 =>  'QuestionState btn btn-default'.($model->published != 1 ? ' btn-danger' : ' btn-success'),
        'data-attribute-questionID' =>  $model->id
    ])
    ?>
    <?= AddQuestionWidget::widget([
        'model'         =>  $model,
        'buttonLabel'   =>  '<i class="glyphicon glyphicon-pencil"></i>',
        'buttonClass'   =>  'btn btn-link'
    ])?>
</div>
    <button type="button" class="btn btn-default changeTrashState" <?=$model->id == '' ? 'disabled="disabled" ' : ''?>data-attribute-questionID="<?=$model->id?>"><?=$model->deleted == "0" ? "Удалить" : "Восттановить";?></button>
<div style="width: 80%; display:block; float:right ; ">
    <span><?=$model->name?></span>
    <br>
    <small style="display: block; opacity: 0.4; max-width: 90%;"><?=$model->phone?></small>
    <small><?=$model->email?></small>

</div>

</div>

<span style=" opacity: 0.4;  text-overflow: ellipsis; ">Вопрос:</span>
<br>
<span style="display: block; opacity: 0.8; width: 70%; overflow-wrap: break-word;"><?=$model->question?></span>
<br>
<span  style=" opacity: 0.4;  text-overflow: ellipsis; ">Ответ:</span>
<br>
<span style="display: block; opacity: 0.8; width: 70%; overflow-wrap: break-word;"><?=$model->answer?></span>
