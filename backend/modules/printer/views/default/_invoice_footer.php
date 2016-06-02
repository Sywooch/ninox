<?php

$withoutDiscountSum = $orderSum = $discountSum = $customerDiscount = 0;

foreach($items as $item){
    $orderSum += ($item->price * ($item->nalichie ? $item->count : 0));

    if(!empty($customer->cardNumber) && $item->discountSize == 2 && $item->discountType == 2 && $item->priceRuleID == 0){
        $customerDiscount += (($item->originalPrice - $item->price) * ($item->nalichie ? $item->count : 0));
    }

    $withoutDiscountSum += ($item->originalPrice * ($item->nalichie ? $item->count : 0));
}

$discountSum = $withoutDiscountSum - $orderSum - $customerDiscount;
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
        if($discountSum > 0){
        ?>
        <tr>
            <td colspan="4"></td>
            <td colspan="2">
                Сумма скидки </td><td border="1" align="right" style="border:1px #000 solid">
                <?=$discountSum?> грн.
            </td>
        </tr>
        <?php
        }

        if($customerDiscount > 0){
        ?>
        <tr>
            <td colspan="4"></td>
            <td colspan="2">
                Дисконт (-2%):</td><td border="1" style="border:1px #000 solid" align="right">
                <?=$customerDiscount?> грн.
            </td>
        </tr>
        <?php
        }
        ?>

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