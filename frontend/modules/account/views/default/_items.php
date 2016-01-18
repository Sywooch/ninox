<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 12/10/2015
 * Time: 2:36 PM
 */
?>
<div class="items">
    <div class="image">
        <img src="<?=$item['image']?>">
    </div>
    <div class="data">
        <div class="order-profile">
            <a><?=$item['order-profile']?></a>
            <span class="order-number">
                №257894
            </span>
        </div>
        <div class="price">
            <div class="one-price">
                <?=$item['one-price']?>
            </div>
            <div class="sum-price">
                <?=$item['sum-price']?>
            </div>
            <div class="sum">
                <?=$item['sum']?> шт.
            </div>
        </div>
    </div>
</div>
