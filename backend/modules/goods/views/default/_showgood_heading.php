<div class="btn-group pull-left" role="group" aria-label="...">
    <?php if($good->show_img != 0 && $good->Deleted != 1){ ?>
        <a href="https://krasota-style.com.ua/tovar/<?=$good->link?>-g<?=$good->ID?>" class="btn btn-default"><i class="glyphicon glyphicon-globe"></i> Посмотреть на сайте</a>
    <?php } ?>
    <?=\common\components\ChangesWidget::widget([
        'model'         =>  $good,
        'header'        =>  'Изменения по товару '.$good->Name
    ]);
    echo \common\components\AddToOrderWidget::widget([
        'modalHeader'        =>  'Добавить товар "'.$good->Name.'" в заказ',
        'itemID'             =>     $good->ID
    ])?>
</div>

<div class="btn-group pull-right" role="group" aria-label="...">
    <button type="button" id="changeTrashState" class="btn btn-info" data-attribute-goodID="<?=$good->ID?>"><?=$good->Deleted == "0" ? "Удалить" : "Восстановить";?></button>
    <button type="button" id="changeState" class="btn btn-info" data-attribute-goodID="<?=$good->ID?>"><?=$good->show_img == "1" ? "Отключить" : "Включить";?></button>
    <a href="/admin/goods/showgood/<?=$good->ID?>?act=edit" type="button" class="btn btn-info">Редактировать</a>
</div>
<div class="clearfix"></div>