<?php
use common\helpers\Formatter;
use yii\bootstrap\Html;


echo /*Html::tag('span', $good->Code, ['class' => 'item-id']),
    Html::tag('span', '', ['class' => 'icon-heart']).
    Html::tag('span', (\Yii::$app->user->isGuest ?
        \Yii::t('shop', 'в избранное') :
        (\Yii::$app->user->identity->hasInWishlist($good->ID) ?
            \Yii::t('shop', 'в избранном') : \Yii::t('shop', 'в избранное'))),
        ['class' => 'item-wish-text']
    ),
    [
        'class' => 'item-wish'.
            (\Yii::$app->user->isGuest ? ' is-guest' : '').
            (\Yii::$app->user->isGuest ?
                $color : (\Yii::$app->user->identity->hasInWishlist($good->ID) ? ' green' : $color)),
        'data-itemId'   =>  $good->ID
    ])*/
Html::tag('span', $good->Code, ['class' => 'item-id']),
    $this->render('/site/_shop_item/_shop_item_wish.php', [
        'model' =>  $good]),
    Html::tag('div', Html::img('http://krasota-style.com.ua/img/catalog/'.$good->ico), ['class' => 'item-image']),
Html::tag('span', $good->Name, ['class' => 'short-description']),
Html::tag('div', Html::tag('span', Formatter::getFormattedPrice($good->wholesalePrice), ['class' => 'wholesale-price semi-bold']).
    Html::tag('span', Formatter::getFormattedPrice($good->retailPrice), ['class' => 'retail-price']).
    Html::tag('div', '', ['class' => 'goods-basket']), [
    'class' =>  'price-and-order'
]);
//$this->render('/site/_shop_item/_shop_item_wish', ['model' => $good])