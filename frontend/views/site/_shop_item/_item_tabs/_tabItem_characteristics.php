<?php
use yii\bootstrap\Html;
?>
<div class="product-characteristics">
    <span class="tabTitle semi-bold">
        <?=\Yii::t('shop', 'Характеристики товара')?>
    </span>
    <div class="details">
        <?php foreach($good->options as $option => $value){
            echo Html::tag('div', Html::tag('div', $option, ['class' => 'characteristic option-name']).Html::tag('div', $value, ['class' => 'option-value']),[
                'class' =>  'characteristics option'
            ]);
        }?>
    </div>
    <!--<div class="properties" itemprop="description"><?/*php$good->Description*/?></div>-->
</div>
<div class="item-detail">
    <span class="tabTitle semi-bold"><?=\Yii::t('shop', 'Описание товара')?></span>
    <div class="details">
        <?=!empty($good->description) ? $good->description : \Yii::t('shop', 'Нет описания')?>
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

