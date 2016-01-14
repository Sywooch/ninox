<div class="col-xs-4">
    <a href="/store/show/<?=$model->id?>">
        <img src="/img/<?=$model->type == \common\models\Shop::TYPE_WAREHOUSE ? 'warehouse' : 'shop'?>.svg" alt="image" style="min-height: 80px; padding: 10px; max-height: 120px;">
        <h1 style="display: inline-block;"><?=$model->name?></h1>
    </a>
</div>