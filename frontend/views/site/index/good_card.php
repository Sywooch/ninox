<?php
use common\helpers\Formatter;
?>
<span class="item-id"><?=$good->Code?></span>
<div class="item-image"><img src="http://krasota-style.com.ua/img/catalog/<?=$good->ico?>"></div>
<span class="short-description"><?=$good->Name?></span>
<div class="price-and-order">
        <span class="wholesale-price semi-bold"><?=Formatter::getFormattedPrice($good->wholesale_price).' '
            .\Yii::$app->params['domainInfo']['currencyShortName']?></span>
        <span class="retail-price"><?=Formatter::getFormattedPrice($good->retail_price).' '
            .\Yii::$app->params['domainInfo']['currencyShortName']?></span>
    <div class="goods-basket"></div>
</div>