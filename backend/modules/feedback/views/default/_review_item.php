<?php
use backend\widgets\AddReviewWidget;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
?>
<div class="pull-right">
    <div style="width: 40%; display: inline-block; padding: 5px;" class="btn-group btn-group-sm">
        <?=Html::button(FA::icon($model->published == 1 ? 'eye' : 'eye-slash'), [
            'class'                 =>  'ReviewState btn btn-default'.($model->published != 1 ? ' btn-danger' : ' btn-success'),
            'data-attribute-reviewID' =>  $model->id
        ])
        ?>
    </div>
    <?= AddReviewWidget::widget([
        'model'         =>  $model,
        'buttonLabel'   =>  '<i class="glyphicon glyphicon-pencil"></i>',
        'buttonClass'   =>  'btn btn-link'
    ])?>
    <a href="#" data-attribute-reviewGroupID="<?=$model->id?>" class="glyphicon glyphicon-trash"></a>
    <br>
    <button type="button" class="btn btn-default changeTrashState" <?=$model->id == '' ? 'disabled="disabled" ' : ''?>data-attribute-reviewID="<?=$model->id?>"><?=$model->deleted == "0" ? "Удалить" : "Восттановить";?></button>
</div>
<div class="col-xs-3" style="max-width: 70px; float: left" >
    <img src="<?=!empty($model->customerPhoto) ? $model->customerPhoto : '/img/noimage.png'?>" style="max-height: 50px; max-width: 50px;" alt="..." class="img-circle">
</div>
<a href="<?=$model->customerID?>" "style="font-size: 18px;">
    <?=$model->name?>
</a>
<span style=" opacity: 0.4;  text-overflow: ellipsis; ">
    <?=$model->customerType?>
</span><br>

<small style="opacity: 0.4;">
    <?=$model->city?>
    <br>
    <?=$model->date?>
</small><br>

<span style=" opacity: 0.4;  text-overflow: ellipsis;">
    <?=$model->review?>
</span>



