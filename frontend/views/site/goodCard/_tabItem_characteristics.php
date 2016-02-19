<div class="product-characteristics">
    <span class="tabTitle semi-bold">
        <?=_("Характеристика товара")?>
    </span>
    <div class="details">
        <div class="characteristics">
            <div class="characteristic">
                <span>
                    <?=!empty($good->num_opt) ? _("Количество в упаковке:") : ''?>
                </span>
            </div>
            <div>
                <span>
                    <?=$good->num_opt.' '.$good->Measure1?>
                </span>
            </div>
        </div>
        <div class="characteristics">
            <div class="characteristic">
                <?=_("Материал:")?>
            </div>
            <div>Ткань тканьевая</div>
        </div>
        <div class="characteristics">
            <div class="characteristic">
                <?=!empty($good->gabarity) ? _("Размеры:") : ''?>
            </div>
            <div>
                <?=$good->gabarity?>
            </div>
        </div>
    </div>
    <!--<div class="properties" itemprop="description"><?/*php$good->Description*/?></div>-->
</div>
<div class="item-detail">
    <span class="tabTitle semi-bold"><?=_("Описание товара")?></span>
    <div class="details">
        <?=!empty($good->Description) ? $good->Description : 'Нет описания'?>
    </div>
</div>

<?php /*} }
/*
if($good['width'] || $good['height'] || $good['length']){
    if($good['width']){
        if($good['width'] < 1){
            $good['width'] = ($good['width'] * 10).'мм';
        }else if($good['width'] > 100){
            $good['width'] = ($good['width'] / 100).'м';
        }else{
            $good['width'] = $good['width'].'cм';
        }
    }
    if($good['height']){
        if($good['height'] < 1){
            $good['height'] = ($good['height'] * 10).'мм';
        }else if($good['width'] > 100){
            $good['height'] = ($good['height'] / 100).'м';
        }else{
            $good['height'] = $good['height'].'cм';
        }
    }
    if($good['length']){
        if($good['length'] < 1){
            $good['length'] = ($good['length'] * 10).'мм';
        }else if($good['width'] > 100){
            $good['length'] = ($good['length'] / 100).'м';
        }else{
            $good['length'] = $good['length'].'cм';
        }
    } ?>
    <div class="properties"><span class="semi-bold blue"><span><?=_("Размеры")?> (<?=($good['width'] ? 'Ш' : '').($good['height'] ? ($good['width'] ? '×В' : 'В') : '').($good['length'] ? (($good['width'] || $good['height']) ? '×Д' : 'Д') : '')?>)</span></span><span><?=($good['width'] ? $good['width'] : '').($good['height'] ? ($good['width'] ? ('×'.$good['height']) : $good['height']) : '').($good['length'] ? (($good['width'] || $good['height']) ? ('×'.$good['length']) : $good['length']) : '')?></span></div>
<?php }elseif($good['gabarity']){ ?>
    <div class="properties"><span class="semi-bold blue"><span><?=_("Размеры")?>:</span></span><span><?=$good['gabarity']?></span></div>
<?php } */?>

