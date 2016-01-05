<?php

$withoutDiscountSum = $orderSum = $discountSum = 0;

foreach($items as $item){
    $orderSum += ($item->price * $item->count);
    $withoutDiscountSum += ($item->originalPrice * $item->count);
}

$discountSum = $withoutDiscountSum - $orderSum;
?>
<hr>
<table width="100%" border="0" style="font-size: 10px; border-collapse: separate; border-spacing: 2px;">
    <tbody>
        <tr>
            <td colspan="4"></td>
            <td colspan="2">Сумма (без скидки):</td>
            <td border="1" style="border:1px #000 solid" align="right"><?=$withoutDiscountSum?> грн.</td>
        </tr>
        <?php
        /*if($orderSum > 800 && $order['kupon'] == '1600271014' && $order['displayorder'] < 1415059200){
        $coupon_proc = 50;
        ?>
        <tr>
            <td colspan="4"></td>
            <td colspan="2">
                Скидка по промокоду </td><td border="1" align="right" style="border:1px #000 solid">
                <? echo "-".$coupon_proc; ?>
            </td>
        </tr>
        <?php
        }*/
        ?>
        <!--<tr>
            <td colspan="4"></td>
            <td colspan="2">
                Дисконт (-2%):</td><td border="1" style="border:1px #000 solid" align="right">
                7.11 грн.
            </td>
        </tr>-->
        <!--<tr>
            <td colspan="4"></td>
            <td colspan="2">Услуги банка (+1%):</td>
            <td border="1" style="border:1px #000 solid" align="right">5.48 грн.</td>
        </tr>-->
        <tr>
            <td colspan="7">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td colspan="2"><b>Сумма к оплате</b>:</td>
            <td border="1" style="border:2px #000 solid" align="right"><?=$orderSum?> грн.</td>
        </tr>
    </tbody>
</table>