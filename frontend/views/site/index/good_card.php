<?php
use common\helpers\Formatter;
use yii\bootstrap\Html;

$buyBlock = function($model){
    return Html::tag('div',
        Html::tag('div',
            Html::tag('div',
                Formatter::getFormattedPrice($model->realWholesalePrice),
                [
                    'class' => ($model->discountType > 0 && $model->priceRuleID == 0 ?
                            'old-wholesale-price' : 'wholesale-price').' gray'
                ]
            ).
            (($model->wholesalePrice != $model->retailPrice || ($model->discountType > 0 && $model->priceRuleID == 0)) ?
                Html::tag('div',
                    Formatter::getFormattedPrice($model->discountType > 0 && $model->priceRuleID == 0 ? $model->wholesalePrice : $model->realRetailPrice),
                    [
                        'class' => ($model->discountType > 0 && $model->priceRuleID == 0 ? 'wholesale-price red' : 'retail-price gray')
                    ]
                ) : ''),
            ['class' => 'price-list']
        ).
        Html::tag('div',
            frontend\widgets\ItemBuyButtonWidget::widget(['model' => $model, 'btnClass' => 'mini-button']),
            ['class' => 'button-block']
        ),
        ['class' => 'price-block']
    );
};

echo Html::tag('span', $good->Code, ['class' => 'item-id']),
    $this->render('/site/_shop_item/_shop_item_wish.php', [
        'model' =>  $good]),
    Html::a(Html::tag('div', Html::img('http://krasota-style.com.ua/img/catalog/'.$good->ico), ['class' => 'item-image']).
        Html::tag('span', $good->Name, ['class' => 'short-description']), '/tovar/'.$good->link.'-g'.$good->ID),
    $buyBlock($good);
