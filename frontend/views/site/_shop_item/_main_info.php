<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 07.07.16
 * Time: 15:44
 */
use common\helpers\Formatter;
use yii\helpers\Html;

?>
<div class="item-main-info">
    <div class="pricelist-content <?=($good->enabled ? 'available' : 'not-available').
    ($good->discountType && $good->customerRule ? ' vip' :
        ($good->discountType && !$good->customerRule ? ' discounted' : ''))?>">
        <?php
        $dopPrice = ($good->discountType && !$good->customerRule) * 1 +
            ($good->discountType && $good->customerRule) * 2 +
            (!$good->discountType && $good->realWholesalePrice != $good->realRetailPrice) * 3;
        switch($dopPrice){
            case 1:
                $dopPrice = Html::tag('div',
                    \Yii::t('shop', '(Экономия: {economy})', [
                        'economy' => Formatter::getFormattedPrice($good->realWholesalePrice - $good->wholesalePrice)
                    ]),
                    ['class' => 'dop-price']
                );
                break;
            case 2:
                $dopPrice = Html::tag('div',
                    \Yii::t('shop', 'опт: {realWholesalePrice}', [
                        'realWholesalePrice'   => Formatter::getFormattedPrice($good->realWholesalePrice)
                    ]).($good->realWholesalePrice == $good->realRetailPrice ? '' : '&nbsp;&nbsp;&nbsp;'.
                        \Yii::t('shop', 'розница: {realRetailPrice}', [
	                        'realRetailPrice'   => Formatter::getFormattedPrice($good->realRetailPrice)
                        ])).
                    Html::tag('span', '?', [
                        'class'         =>  'question-round-button',
                        'data-toggle'   =>  'tooltip',
                        'title'    =>  \Yii::t('shop', 'Эта сумма может измениться, в случае если вдруг не будет товаров на складе')
                    ]),
                    ['class' => 'dop-price']
                );
                break;
            case 3:
                $dopPrice = Html::tag('div',
                    \Yii::t('shop', 'розничная цена: {realRetailPrice}', [
                        'realRetailPrice'   => Formatter::getFormattedPrice($good->realRetailPrice)
                    ]).
                    Html::tag('span', '?', [
                        'class'         =>  'question-round-button',
                        'data-toggle'   =>  'tooltip',
                        'title'    =>  \Yii::t('shop', 'Эта сумма может измениться, в случае если вдруг не будет товаров на складе')
                    ]),
                    ['class' => 'dop-price']
                );
                break;
            default:
                $dopPrice = '';
                break;
        };

        echo Html::tag('div',
                ($good->enabled ?
                    ($good->count < 1 ?
                        \Yii::t('shop', 'Под заказ.') : \Yii::t('shop', 'Есть в наличии')) :
                    \Yii::t('shop', 'Нет в наличии')),
                [
                    'class'     =>  'availability',
                    'itemprop'  =>  'availability',
                    'href'      =>  'http://schema.org/InStock'
                ]
            ).
            Html::tag('div',
                ($good->discountType && !$good->customerRule ?
                    Html::tag('div',
                        \Yii::t('shop', 'старая цена: {oldPrice}', [
	                        'oldPrice' => Html::tag('div', Formatter::getFormattedPrice($good->realWholesalePrice),
		                        ['class' => 'old-wholesale-price']
	                        )
                        ]),
                        ['class' => 'old-price']
                    ) : ''
                ).
                Html::tag('div', Formatter::getFormattedPrice($good->wholesalePrice), ['class' => 'wholesale-price']).
                $dopPrice,
                ['class' => 'price-list']
            ).
            $this->render('_shop_item_counter', ['model' => $good]).
            frontend\widgets\ItemBuyButtonWidget::widget(['model' => $good, 'btnClass' => 'large-button']),
        Html::tag('div',
            ($good->customerRule ?
                Html::tag('span', \Yii::t('shop', 'Нашли дешевлее?'), ['class' => 'cheaper blue']) : ''
            ).
            Html::tag('span', $good->enabled == 1 ?
                \Yii::t('shop', 'Узнать о снижении цены') : \Yii::t('shop', 'Узнать когда появится'),
                [
                    'class'     =>  'notification blue'
                ]
            ).
            $this->render('_shop_item_wish', ['model' => $good, 'color' => 'blue']),
            [
                'class' =>  'about-price italic'
            ]
        ),
        Html::tag('div',
            $this->render('_shop_item_rate', ['model' => $good]),
            ['class' => 'item-rating']
        )?>
    </div>
    <?=($good->garantyShow == '1' && $good->anotherCurrencyPeg == '1' ?
        Html::tag('div', Html::tag('span', \Yii::t('shop', 'Внимание!')).
            ' '.
            \Yii::t('shop', 'Цена действительна при оплате заказа {date} до 23:59', [
                'date'  =>  date('d.m.Y')
            ]), [
            'class' => 'pricelist-warning'
        ]) : '')
    ?>
</div>