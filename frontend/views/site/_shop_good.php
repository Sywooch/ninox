<?php

use common\helpers\Formatter;
use yii\helpers\Html;

$link = '/tovar/'.$model->link.'-g'.$model->ID;
$photo = \Yii::$app->params['cdn-link'].\Yii::$app->params['small-img-path'].$model->ico;
$flags = [];
$blocks = [];

$createFlag = function($name, $background){
	return Html::tag('div', $name, [
		'class' =>  'capt-flag '.$background
	]);
};

if($model->isNew){
    $flags[] = $createFlag(\Yii::t('shop', 'Новинка'), 'bg-capt-green');
}
if($model->originalGood){
    $flags[] = $createFlag(\Yii::t('shop', 'Оригинал'), 'bg-capt-purple');
}
if($model->discountType > 0 && $model->priceRuleID == 0){
    $flags[] = $createFlag(\Yii::t('shop', 'Распродажа'), 'bg-capt-red');
}

$flags = $flags ? Html::tag('div', implode('',$flags), ['class' => 'capt-flags']) : '';

if(!empty($model->video)){
    $blocks[] = Html::tag('div', \Yii::t('shop', 'Видео о товаре'), [
        'class'     =>  'goods-bottom-video link-hide',
        'data-href' =>  $link.'#tab-video'
    ]);
}

if(!\Yii::$app->user->isGuest){
    $blocks[] = Html::tag('div', \Yii::t('shop', 'В список желаний'), [
        'class' =>  'goods-bottom-desire'
    ]);
}

$countBlock = function($model){
    if($model->isUnlimited || $model->count > 9){
        $name = \Yii::t('shop', 'достаточно');
        $class = 'green';
    }else{
        if($model->count > 1){
            $name = \Yii::t('shop', 'заканчивается');
            $class = 'red';
        }else if($model->count <= 0){
            $name = \Yii::t('shop', 'нет в наличии');
            $class = 'gray';
        }else{
            $name = \Yii::t('shop', 'последний');
            $class = 'black bold';
        }
    }

	return Html::tag('div',
		Html::tag('div',
			Html::tag('div', \Yii::t('shop', 'Остаток на складе:'), []).
			Html::tag('div', $name, ['class' => $class]),
			['class' => 'item-count-info font-size-13px']
		).
		\app\widgets\CartItemsCounterWidget::widget(['model' => $model]),
		['class' => 'item-counter-info']
	);
};

$buyBlock = function($model){
	$button = [
		'value'         =>  $model->count > 0 || $model->isUnlimited ?
			($model->inCart ?
				\Yii::t('shop', 'В корзине!') : \Yii::t('shop', 'Купить!')
			) : \Yii::t('shop', 'Нет в наличии'),
		'class'         =>  ($model->count > 0 || $model->isUnlimited ?
			($model->inCart ?
				'green-button open-cart' : 'yellow-button buy'
			) : 'gray-button out-of-stock').' small-button',
		'data-itemId'   =>  $model->ID,
		'data-count'    =>  '1',
	];
	return Html::tag('div',
		Html::tag('div',
			Html::tag('div',
				Formatter::getFormattedPrice($model->discountType > 0 && $model->priceRuleID == 0 ?
					$model->wholesale_real_price : $model->wholesale_real_price),
				[
					'class' => ($model->discountType > 0 && $model->priceRuleID == 0 ?
							'old-wholesale-price' : 'wholesale-price').' gray'
				]
			).
			(($model->wholesale_price != $model->retail_price || ($model->discountType > 0 && $model->priceRuleID == 0)) ?
				Html::tag('div',
					Formatter::getFormattedPrice($model->discountType > 0 && $model->priceRuleID == 0 ? $model->wholesale_price : $model->retail_real_price),
					[
						'class' => ($model->discountType > 0 && $model->priceRuleID == 0 ? 'wholesale-price red' : 'retail-price gray')
					]
				) : ''),
			['class' => 'price-list']
		).
		Html::tag('div',
			Html::input('button', null, $button['value'], $button),
			['class' => 'button-block']
		),
		['class' => 'price-block']
	);
};

$discountBlock = function($model){
	switch($model->discountType){
		case 1:
			$dimension = ' '.\Yii::$app->params['domainInfo']['currencyShortName'];
			break;
		case 2:
			$dimension = '%';
			break;
		default:
			$dimension = '';
			break;
	}

	return $model->priceRuleID ? Html::tag('div',
		Html::tag('div',
			Html::tag('div', $model->customerRule ? \Yii::t('shop', 'Опт') : \Yii::t('shop', 'Акция')).
			Html::tag('div', '-'.$model->discountSize.$dimension),
			['class' => 'top']).
		Html::tag('div',
			Html::tag('div', Formatter::getFormattedPrice($model->wholesale_price), ['class' => 'semi-bold']).
			Html::tag('div', \Yii::$app->params['domainInfo']['currencyShortName']),
			['class' =>  'bottom']
		),
		['class' => 'discount']
	) : '';
};

echo Html::tag('div',
	Html::tag('div',
		Html::tag('div',
			Html::tag('div', \Yii::t('shop', 'в избранное'), ['class' => 'item-wish']).
			Html::tag('div', $model->Code, ['class' => 'item-code']),
			['class' => 'item-head']).
		Html::tag('div',
			Html::img($photo,[
				'class' => 'link-hide',
				'data-href' => $link,
				'alt' => $model->Name,
				'title' => $model->Name.' - '.\Yii::t('shop', 'оптовый интернет-магазин Krasota-Style'),
				'height' => 190,
				'width' => 255
			]).
			$discountBlock($model).
			$flags,
			['class' => 'item-img']).
		Html::a($model->Name, $link, [
			'class' =>  'blue',
			'title' =>  $model->Name
		]).
		$buyBlock($model),
		['class' => 'inner-main']).
	Html::tag('div',
		$countBlock($model),
		['class' => 'inner-sub']),
	['class' => 'item']);
?>
<!--<div class="item">
    <div class="inner-main">
        <div class="title">
            <a class="blue" href="<?/*=$link*/?>" title="<?/*=$model->Name*/?>"><?/*=$model->Name*/?></a>
        </div>
        <div class="code"><?/*=\Yii::t('shop', 'Код')*/?>: <?/*=$model->Code*/?></div>
	    <div class="favorites" style="width: 10px; height: 10px;"></div>
        <div class="open-item">
            <img class="link-hide" data-href="<?/*=$link*/?>" alt="<?/*=$model->Name*/?>" src="<?/*=$photo*/?>" title="<?/*=$model->Name*/?> - <?/*=\Yii::t('shop', 'оптовый интернет-магазин Krasota-Style')*/?>" height="190" width="243">
            <?php /*if($model->priceRuleID){
                echo $discountBlock($model);
            }

            if(!empty($flags)){
                echo Html::tag('div', implode('', $flags), [
                    'class' =>  'capt-flags'
                ]);
            }
            */?>
            <div class="open-modal-item" data-itemId="<?/*=$model->Code*/?>"></div>
        </div>
        <?/*=$buyBlock($model, $button)*/?>
    </div>
    <div class="inner-sub">
	    <?php /*if($model->count > 0){
		    echo \app\widgets\CartItemsCounterWidget::widget([
			    'itemID'    =>  $model->ID,
			    'value'     =>  $model->inCart ? $model->inCart : 1,
			    'store'     =>  $model->isUnlimited ? 1000 : $model->count,
			    'inCart'    =>  $model->inCart,
		    ]);
	    }*/?>
        <div class="item-info">
            <?php /*if($model->count < 1 && $model->isUnlimited){ */?>
            <div>
                <span>Под заказ. Доставка: 1 - 4 дня.</span>
            </div>
            <?php /*}else{
                if($model->priceForOneItem){
            */?>
            <div>
            <?/*=\Yii::t('shop', 'Цена за единицу')*/?>:
                <span class="inner-span semi-bold">
                <?/*=$model->priceForOneItem.' '.\Yii::$app->params['domainInfo']['currencyShortName'].' ('.$model->num_opt.' '.$model->Measure1.'/'.\Yii::t('shop', 'уп').')'*/?>
                </span>
            </div>
                <?php /*} */?>
            <div class="count-in-store">
                <span>Остаток на складе:&nbsp;</span>
                <?/*=$countInStore($model)*/?>
            </div>
            <?php /*} */?>
            <div class="rating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                <span class="currentRating" itemprop="ratingValue"><?/*=($model->rate ? $model->rate : 5)*/?></span>
                <div class="shop-star<?/*=$model->rate == 5 ? ' current' : ''*/?>" itemprop="bestRating" title="<?/*=\Yii::t('shop', 'Отлично')*/?>" data-item="<?/*=$model->ID*/?>" data-rate="5">5</div>
                <div class="shop-star<?/*=4 <= $model->rate && $model->rate < 5 ? ' current' : ''*/?>" title="<?/*=\Yii::t('shop', 'Хорошо')*/?>" data-item="<?/*=$model->ID*/?>" data-rate="4">4</div>
                <div class="shop-star<?/*=3 <= $model->rate && $model->rate < 4 ? ' current' : ''*/?>" title="<?/*=\Yii::t('shop', 'Средне')*/?>" data-item="<?/*=$model->ID*/?>" data-rate="3">3</div>
                <div class="shop-star<?/*=2 <= $model->rate && $model->rate < 3 ? ' current' : ''*/?>" title="<?/*=\Yii::t('shop', 'Приемлемо')*/?>" data-item="<?/*=$model->ID*/?>" data-rate="2">2</div>
                <div class="shop-star<?/*=1 <= $model->rate && $model->rate < 2 ? ' current' : ''*/?>" itemprop="worstRating" title="<?/*=\Yii::t('shop', 'Плохо')*/?>" data-item="<?/*=$model->ID*/?>" data-rate="1">1</div>
                <span class="rateCount" itemprop="reviewCount"><?/*=$model->reviewsCount ? $model->reviewsCount : 1*/?></span>
            </div>
            <div class="goods-comments">
                <span class="link-hide blue shop-comment-empty" data-href="<?/*=$link*/?>#tab-reviews">
                    <?/*=Yii::t('shop', '{n, number} {n, plural, one{отзыв} few{отзыва} many{отзывов} other{отзывов}}', [
                        'n' =>  $model->reviewsCount
                    ])*/?>
                </span>
            </div>
        </div>
        <?php
/*        if(!empty($blocks)){
            echo Html::tag('div', implode('', $blocks), [
                'class' =>  'goods-bottom'
            ]);
        }
        */?>
    </div>
</div>-->