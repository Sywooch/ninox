<?php
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
?>
<div style="display: ; padding: ; " class="pull-right">
    <div style="width: 30%; display: inline-block; padding: 5px;" class="btn-group btn-group-sm">
        <?=Html::button(FA::icon($model->published == 1 ? 'eye' : 'eye-slash'), [
            'class'                 =>  'ReviewState btn btn-default'.($model->published != 1 ? ' btn-danger' : ' btn-success'),
            'data-attribute-reviewID' =>  $model->id
        ])
        ?>
    </div>
    <?=\backend\widgets\AddReviewGroupWidget::widget([
        'model'         =>  $model,
        'buttonLabel'   =>  '<i class="glyphicon glyphicon-pencil"></i>',
        'buttonClass'   =>  'btn btn-link'
    ])?>
    <a href="#" data-attribute-reviewGroupID="<?=$model->id?>" class="glyphicon glyphicon-trash"></a>
</div>
<div class="col-xs-3" style="max-width: 70px; float: left" >
    <img src="/img/noimage.png" style="max-height: 50px;" alt="..." class="img-circle">
</div>

<a " style="font-size: 18px;"><?=$model->name?></a>
<span><?=$model->customerType?></span>
<br>
<small   style="display: ;
         opacity: 0.4;
         text-overflow: ellipsis;
         overflow: hidden;
         white-space: nowrap;
         max-width: ;">
    <?=$model->city?>
    <br>
    <?=$model->date?>
</small><br>
<a "style="float:right; padding-bottom:20px; opacity: 0.4;  text-overflow: ellipsis;"><?=$model->review?></a>



