<?php
$dayofweek = [
    "Воскресенье",
    "Понедельник",
    "Вторник",
    "Среда",
    "Четверг",
    "Пятница",
    "Суббота"
];
?>
<table width="100%">
    <tr>
        <td colspan="4">
            <img src="/template/images/krasota-style-logonakladna.png" width="246" height="71"/>
        </td>
        <td style="font-size:26px; text-align:right; font-weight: bold;" colspan="4">
            <img src="/template/images/icon-phone-nakladna.png" width="23" height="16"> 0 800 508 208
        </td>
    </tr>
</table>
<br>
<hr>
<br>
<span style="font-size:26px;">Заказ №<?=$order->id?> от <?=\Yii::$app->formatter->asDatetime($order->added, "d.m.y - H:i")?> <span style="font-size:18px;"><?=$dayofweek[date("w", $order->added)]?></span></span>
<img class="barcode" src="data:image/jpg;base64,<?=''//$barcode['img']?>">
<br><br>
<?=$order->customerSurname?> <?=$order->customerName?> &nbsp;&nbsp;&nbsp;
<?=$order->customerPhone?><br>
г. <?=$order->deliveryCity?>, <?=$order->deliveryRegion?><br>
<?=$order->deliveryAddress?><br>
<hr>
<br>
<table class="print_excel"><tr><th>№</th><th>Код товара</th><th>Наименование</th><th>Кол.</th><th>Цена (грн.)</th><th>Сумма</th><th>Скидка (%)</th></tr>
    <?php
    /*
    foreach($order['goods'] as $good){
        $i++;
        $zamena = $good['zamena'] == 1 ? " (замена)" : "";
        if($good['nezakaz'] == 0 && $good['nalichie'] == 0){
            $price = "нет в наличии";
            $content .= '<tr><td>'.$i.'</td>
						<td>'.$good['Code'].'</td>
						<td>'.$good['sborka_tov_name'].$zamena.'</td>
						<td>'.$good['Qtty'].'</td>
						<td colspan="3" align="center">'.$price.'</td></tr>';
        }elseif($good['nezakaz'] == 0 && $good['nalichie'] == 1){
            $price = $good['discountPrice'] ? '<s>'.$good['realPrice'].'</s>&nbsp;&nbsp;'.$good['PriceOut'].'<sup>'.($good['priceRuleID'] == 0 ? '**' : '*').'</sup>' : $good['PriceOut'];

            $price_sum = ($good['PriceOut'] * $good['Qtty']);
            $content .= '<tr><td>'.$i.'</td>
						<td>'.$good['Code'].'</td>
						<td>'.$good['sborka_tov_name'].$zamena.'</td>
						<td>'.$good['Qtty'].'</td>
						<td>'.$price.'</td>
						<td>'.$price_sum.'</td>
						<td>'.($good['priceRuleID'] == 0 ? '' : round($good['discountSize'], 0)).'</td></tr>';
            $totalsumm_all += $price_sum;
            $sumToDiscount += $good['discountPrice'] ? 0 : $price_sum;
            $summ_witout_discount += $good['discountPrice'] ? ($good['realPrice'] * $good['Qtty']) : $price_sum;
            $saleDisc += ($good['priceRuleID'] == 0 && $good['realPrice'] != 0) ? (($good['realPrice'] - $good['PriceOut']) * $good['Qtty']) : 0;
            $actionDisc += $good['priceRuleID'] != 0 ? (($good['realPrice'] - $good['PriceOut']) * $good['Qtty']) : 0;
        }
    }
    */

    echo $content.'</table>
			<hr>';

    echo '<table border="0" width="100%">';

    echo '<tr> <td colspan="4"></td><td  colspan="2">Сумма (без скидки):</td><td border="1" align="right" style="border:1px #000 solid">'.$summ_witout_discount.' грн.</td></tr>';

    if($actionDisc){
        echo '<tr> <td colspan="4"></td><td  colspan="2"><sup>*</sup>Сумма скидки на акционный товар:</td><td border="1" align="right" style="border:1px #000 solid">'.round($actionDisc, 2) .' грн.</td></tr>';
    }
    if($saleDisc){
        echo '<tr> <td colspan="4"></td><td  colspan="2"><sup>**</sup>Сумма скидки на товар из распродажи:</td><td border="1" align="right" style="border:1px #000 solid">'.round($saleDisc, 2) .' грн.</td></tr>';
    }

    if($totalsumm_all > 800 && $order['kupon'] == '1600271014' && $order['displayorder'] < 1415059200){
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
    }

    if($partner['CardNumber'] <> 0 && $sumToDiscount > 0){
        $msg_discont_skidka = 2;
        $discount_proc = round(($msg_discont_skidka * $sumToDiscount / 100), 2);
        ?>
        <tr>
            <td colspan="4"></td>
            <td colspan="2">
                Дисконт (-<?=$msg_discont_skidka?>%):</td><td border="1" align="right" style="border:1px #000 solid">
                <?=$discount_proc?> грн.
            </td>
        </tr>
    <?php
    }else{
        $discount_proc = 0;
    }

    if($order['amountDeductedOrder']<>0){ ?>
    <tr>
        <td colspan="4"></td>
        <td colspan="2">
            Списано со счета :</td><td border="1" align="right" style="border:1px #000 solid">
            <?=$order['amountDeductedOrder']?> грн.
        </td>
    </tr>
<?php
}

if($order['plateg'] == $paymentsDetails['2']['name']){
    $bankPercent = round((($totalsumm_all - $discount_proc - $coupon_proc - $order['amountDeductedOrder']) * 0.01) + 2, 2);
    echo '<tr> <td colspan="4"></td><td  colspan="2">Услуги банка (+1%):</td><td border="1" align="right" style="border:1px #000 solid">'.$bankPercent.' грн.</td></tr>';
}else{
    $bankPercent = 0;
}

echo '<tr> <td colspan="7">&nbsp;</td></tr>
			<tr><td colspan="4"></td><td  colspan="2"><b>Сумма к оплате</b>:</td><td border="1" align="right" style="border:2px #000 solid">'.round(($totalsumm_all + $bankPercent - $discount_proc - $coupon_proc - $order['amountDeductedOrder']), 2).' грн.</td></tr>
			</table>';