<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 10.03.16
 * Time: 11:49
 */
use common\helpers\Formatter;

?>
<div>
    <span class="icons-fav-bask"></span>
    <span><?=$good->Name?></span>
    <span class="price"><?=Formatter::getFormattedPrice($good->wholesale_price).' '
        .\Yii::$app->params['domainInfo']['currencyShortName']?>
    </span>
</div>
<div><img src="http://krasota-style.com.ua/img/catalog/<?=$good->ico?>"></div>
