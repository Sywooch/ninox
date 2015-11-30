<div style="display: ; padding: ; " class="pull-right">
    <?=\backend\widgets\AddReviewGroupWidget::widget([
        'model'         =>  $model,
        'buttonLabel'   =>  '<i class="glyphicon glyphicon-pencil"></i>',
        'buttonClass'   =>  'btn btn-link'
    ])?>
    <a href="#" data-attribute-reviewGroupID="<?=$model->id?>" class="glyphicon glyphicon-trash"></a>
</div>
<div class="col-xs-3" style="max-width: 110px; float: left" >
    <img src="/img/noimage.png" style="max-height: 90px;" alt="..." class="img-circle">
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
<a " style="float:right; padding-bottom:20px; opacity: 0.4;  text-overflow: ellipsis;

"><?=$model->review?></a>



