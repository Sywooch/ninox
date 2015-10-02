<a href="/admin/banners/showbanners/<?=$model->id?>" style="font-size: 18px;"><?=$model->description?></a>
<div class="pull-right" style="line-height: 28px">
    <span class="badge"><?=$model->bannersCount()?></span>
    <?=\app\components\AddBannerGroupWidget::widget([
        'model'         =>  $model,
        'buttonLabel'   =>  '<i class="glyphicon glyphicon-pencil"></i>',
        'buttonClass'   =>  'btn btn-link'
    ])?>
    <a href="#" data-attribute-bannerGroupID="<?=$model->id?>" class="glyphicon glyphicon-trash"></a>
</div>