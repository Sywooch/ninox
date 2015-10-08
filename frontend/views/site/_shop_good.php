<?php

use yii\helpers\Html;

$link = '/tovar/'.$model->link.'-g'.$model->ID;
$photo = \Yii::$app->params['cdn-link'].'/img/catalog/sm/'.$model->ico;
$flags = [];

if($model->isNew){
    $flags[] = \yii\helpers\Html::tag('div', \Yii::t('shop', 'Новинка'), [
        'class' =>  'capt-flag bg-capt-green'
    ]);
}

if($model->originalGood){
    $flags[] = \yii\helpers\Html::tag('div', \Yii::t('shop', 'Оригинал'), [
        'class' =>  'capt-flag bg-capt-purple'
    ]);
}
/*if($model->PrOut3){
    $flags[] = \yii\helpers\Html::tag('div', \Yii::t('shop', 'Распродажа'), [
        'class' =>  'capt-flag bg-capt-red'
    ]);
}*/

$blocks = [];

if(!empty($model->video)){
    $blocks[] = Html::tag('div', \Yii::t('shop', 'Видео о товаре'), [
        'class'     =>  'goods-bottom-video link-hide',
        'data-href' =>  $link.'#tab-video'
    ]);
}

if(!empty($_SESSION['userID'])){
    $blocks[] = Html::tag('div', \Yii::t('shop', 'В список желаний'), [
        'class' =>  'goods-bottom-desire'
    ]);
}

$goodsOst = function($model){
    if($model->isUnlimited || $model->count > 9){
        $name = \Yii::t('shop', 'Достаточно');
        $class = 'green';
    }else{
        if($model->count > 3){
            $name = \Yii::t('shop', 'Мало');
            $class = 'brown';
        }else if($model->count <= 0){
            $name = \Yii::t('shop', 'Нет в наличии');
            $class = 'red';
        }else{
            $name = \Yii::t('shop', 'Заканчивается');
            $class = 'red';
        }
    }

    return Html::tag(
        'span',
        $name,
        [
            'class' =>  'progress-'.$class
        ]
    );
};

$button = [
    'value'         =>   \Yii::t('shop', $model->inCart ? 'В корзине!' : 'Купить!'),
    'class'         =>  ($model->inCart ? 'greenButton openCart' : 'yellowButton buy').' middleButton',
    'data-itemId'   =>  $model->Code,
    'data-count'    =>  '1'
];

?>
<div class="hovered">
    <div class="item">
        <div class="inner">
            <div class="title">
                <a class="blue" href="<?=$link?>" title="<?=$model->Name?>"><?=$model->Name?></a>
            </div>
            <div class="code"><?=\Yii::t('shop', 'Код')?>: <?=$model->Code?></div>
            <div class="openItem">
                <img class="link-hide" data-href="<?=$link?>" alt="<?=$model->Name?>" src="<?=$photo?>" title="<?=$model->Name?> - <?=\Yii::t('shop', 'оптовый интернет-магазин Krasota-Style')?>" height="190" width="243">
                <?php if($model->discount){ ?>
                    <div class="discount">
                        <div class="top">
                            <span><?=\Yii::t('shop', 'Акция')?></span>
                            <span>-<?=''//$model['discount']['discountSize']?>%</span>
                        </div>
                        <div class="bottom">
                            <span><?=''//number_format($oneItem['discount']['discountPrice'] * $_SESSION['domainInfo']['exchange'], $_SESSION['domainInfo']['coins'] ? 2 : 0, '.', ' ')?></span>
                            <span><?=\Yii::$app->params['currencyShortName']?></span>
                        </div>
                    </div>
                <?php }?>
                <?php
                if(!empty($flags)){
                    echo \yii\helpers\Html::tag('div', implode('', $flags), [
                        'class' =>  'capt-flags'
                    ]);
                }
                ?>
                <div class="openModalItem" data-itemId="<?=$model->Code?>"></div>
            </div>
            <div class="pricelist">
                <div>
                    <div class="<?=''//($oneItem['PrOut3'] ? 'new-' : '')?>opt semi-bold <?=''//($oneItem['PrOut3'] ? 'red' : 'blue')?>">
                        <span><?=''//$oneItem['PrOut1']['0']?></span><?=''//$oneItem['PrOut1']['1']?><span class="uah"> <?=\Yii::$app->params['currencyShortName']?></span>
                    </div>
                    <?php// if(!$oneItem['onePrice'] || $oneItem['PrOut3']){ ?>
                        <div class="<?=''//($oneItem['PrOut3'] ? 'old-' : 's')?>opt">
                            <span><?=''//($oneItem['PrOut3'] ? _("опт") : _("розница"))?> - <?=''//($oneItem['PrOut3'] ? $oneItem['PrOut3']['0'] : $oneItem['PrOut2']['0'])?></span><?=''//($oneItem['PrOut3'] ? $oneItem['PrOut3'][1] : $oneItem['PrOut2'][1])?><span> <?=\Yii::$app->params['currencyShortName']?></span>
                        </div>
                    <?php// } ?>
                </div>
                <?php
                echo Html::tag(
                    'div',
                    $model->Code ? Html::input('button', null, $button['value'], $button) : \Yii::t('shop', $model->count <= 0 ? 'Нет в наличии' : 'Ожидается поступление'),
                    [
                        'class' => $model->Code ? 'canBuy' : 'expectedArrival semi-bold'
                    ]
                );
                ?>
            </div>
        </div>
        <div class="dopInfo">
            <?php if($model->canBuy){ ?>
                <div class="counter">
                    <a class="minus" data-itemId="<?=$model->Code?>"></a>
                    <input value="<?=$model->inCart ? $model->inCart : 1?>" readonly="readonly" name="count" class="count" type="text" data-itemId="<?=$model->Code?>">
                    <a class="plus" data-itemId="<?=$model->Code?>" data-countInStore="<?=($model->isUnlimited ? 1000 : $model->count)?>"></a>
                </div>
            <?php
            }
            ?>
            <div class="progress">
                <?php if($model->count < 1 && $model->isUnlimited){ ?>
                <div>
                    <span>Под заказ. Доставка: 1 - 4 дня.</span>
                </div>
                <?php }else{
                    if($model->priceForOneItem){
                ?>
                <span class="outerSpan">
                <?=\Yii::t('shop', 'Цена за единицу')?>:
                    <span class="innerSpan semi-bold">
                    <?=$model->priceForOneItem.' '.\Yii::$app->params['currencyShortName'].' ('.$model->num_opt.' '.$model->Measure1.'/'.\Yii::t('shop', 'уп').')'?>
                    </span>
                </span>
                <?php } ?>
                <div class="goods-ost">
                    <span>Остаток на складе:&nbsp;</span>
                    <?php
                    echo $goodsOst($model);
                    ?>

                </div>
                <?php } ?>
                <div class="rating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                    <span class="currentRating" itemprop="ratingValue"><?=($model->rate ? $model->rate : 5)?></span>
                    <div class="shop-star<?=$model->rate == 5 ? ' current' : ''?>" itemprop="bestRating" title="<?=\Yii::t('shop', 'Отлично')?>" data-item="<?=$model->ID?>" data-rate="5">5</div>
                    <div class="shop-star<?=4 <= $model->rate && $model->rate < 5 ? ' current' : ''?>" title="<?=\Yii::t('shop', 'Хорошо')?>" data-item="<?=$model->ID?>" data-rate="4">4</div>
                    <div class="shop-star<?=3 <= $model->rate && $model->rate < 4 ? ' current' : ''?>" title="<?=\Yii::t('shop', 'Средне')?>" data-item="<?=$model->ID?>" data-rate="3">3</div>
                    <div class="shop-star<?=2 <= $model->rate && $model->rate < 3 ? ' current' : ''?>" title="<?=\Yii::t('shop', 'Приемлемо')?>" data-item="<?=$model->ID?>" data-rate="2">2</div>
                    <div class="shop-star<?=1 <= $model->rate && $model->rate < 2 ? ' current' : ''?>" itemprop="worstRating" title="<?=\Yii::t('shop', 'Плохо')?>" data-item="<?=$model->ID?>" data-rate="1">1</div>
                    <span class="rateCount" itemprop="reviewCount"><?=$model->reviewsCount ? $model->reviewsCount : 1?></span>
                </div>
                <div class="goods-comments">
                    <span class="link-hide blue" data-href="<?=$link?>#tab-reviews">
                        <?=Yii::t('shop', '{n, number} {n, plural, one{отзыв} few{отзыва} many{отзывов} other{отзывов}}', [
                            'n' =>  $model->reviewsCount
                        ])?>
                    </span>
                </div>
            </div>
            <?php
            if(!empty($blocks)){
                echo Html::tag('div', implode('', $blocks), [
                    'class' =>  'goods-bottom'
                ]);
            }

            ?>
        </div>
    </div>
</div>