
<div class="col-xs-3">
    <img src="/img/noimage.png" style="max-height: 50px;" alt="..." class="img-circle">
</div>
<a " style="font-size: 18px;"><?=$model->name?></a>

<div style="display: ; padding: ;" class="pull-right">
    <?=\backend\widgets\AddReviewGroupWidget::widget([
        'model'         =>  $model,
        'buttonLabel'   =>  '<i class="glyphicon glyphicon-pencil"></i>',
        'buttonClass'   =>  'btn btn-link'
    ])?>
    <a href="#" data-attribute-bannerGroupID="<?=$model->id?>" class="glyphicon glyphicon-trash"></a>
</div>
<span><?=$model->customerType?></span>
<br>
<small   style="display: ;
         opacity: 0.4;
         text-overflow: ellipsis;
         overflow: hidden;
         white-space: nowrap;
         max-width: ;">
    <?=$model->city?>
</small>

