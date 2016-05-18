<?php
use kartik\editable\Editable;

$good = $model;
?>
<div class="col-sm-4 col-md-3" style="min-height: 500px">
    <div class="thumbnail <?=$good->Deleted == "1" ? "bg-very-danger" : ($good->enabled == '1' ? "bg-success" : "bg-danger")?>" data-value-goodID="<?=$good->ID?>"<?=$good->Deleted == "1" ? " data-attribute-deleted=\"1\"" : ""?>>
        <img<?=($good->PriceOut3 != "" && $good->PriceOut3 != "0") ? ' class="good-sale"' : ''?> src="<?=\Yii::$app->params['cdn-link']?>/img/catalog/sm/<?=$good->photo?>" alt="<?=$good->Name?>">
        <div class="caption">
            <dl>
                <dt>Название:</dt>
                <dd><?=$good->Name?></dd>
                <dt>Код товара:</dt>
                <dd><?=$good->Code?></dd>
                <dt>Цена опт:</dt>
                <dd><?=$good->PriceOut1?> грн.</dd>
                <dt>Цена розница:</dt>
                <dd><?=$good->PriceOut2?> грн.</dd>
                <dt>Цена в валюте:</dt>
                <!--<dd><?=Editable::widget([
                        'model' =>  $good,
                        'containerOptions'   =>  [
                            'style' =>  'line-height: 12px'
                        ],
                        'size'  =>  'md',
                        'attribute' =>  'anotherCurrencyValue',
                        'ajaxSettings'  =>  [
                            'type'      =>  'post',
                            'url'       =>  '/goods/simplegoodedit',
                        ],
                    ]),
                    Editable::widget([
                        'model' =>  $good,
                        'containerOptions'   =>  [
                            'style' =>  'line-height: 12px'
                        ],
                        'size'  =>  'md',
                        'attribute' =>  'anotherCurrencyTag',
                        'inputType' =>  Editable::INPUT_DROPDOWN_LIST,
                        'data'  =>  [
                            'usd'   =>  'USD',
                            'eur'   =>  'EUR'
                        ],
                        'ajaxSettings'  =>  [
                            'type'      =>  'post',
                            'url'       =>  '/goods/simplegoodedit',
                        ],
                    ])?></dd>-->
                <dd><?=$good->anotherCurrencyValue?> <?=$good->anotherCurrencyTag?></dd>
                <dt>Остаток:</dt>
                <dd><?=$good->count?> шт.</dd>
                <dt>Создан:</dt>
                <dd><?=$good->tovdate != '0000-00-00 00:00:00' ? \Yii::$app->formatter->asDatetime($good->tovdate, "php:d.m.Y H:i") : "-";?></dd>
                <dt>Отключен:</dt>
                <dd><?=$good->otkl_time != '0000-00-00 00:00:00' ? \Yii::$app->formatter->asDatetime($good->otkl_time, "php:d.m.Y H:i") : "-";?></dd>
            </dl>
            <div class="btn-group" role="group" aria-label="...">
                <div class="btn-group">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        Действия <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="/goods/view/<?=$good->ID?>?act=edit" data-pjax="0">Редактировать</a></li>
                        <li id="good-state"><a style="cursor: pointer;" class="changeState-btn"><?=$good->enabled == "1" ? "Отключить" : "Включить"?></a></li>
                        <li><a style="cursor: pointer;" class="up-btn">Поднять товар</a></li>
                        <li><a style="cursor: pointer;" class="print-btn">Печать</a></li>
                        <?php if($good->enabled != 0 && $good->Deleted != 1){ ?>
                            <li><a href="https://krasota-style.com.ua/tovar/<?=$good->link?>-g<?=$good->ID?>">Посмотреть на сайте</a></li>
                        <?php } ?>
                        <li class="divider"></li>
                        <li id="deleted-state"><a style="cursor: pointer;" class="delete-btn"><?=$good->Deleted == "1" ? "Восстановить" : "Удалить"?></a></li>
                    </ul>
                </div>
                <a href="/goods/view/<?=$good->ID?>" type="button" class="btn btn-default" data-pjax="0">Подробнее</a>
            </div>
        </div>
    </div>
</div>