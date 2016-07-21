<hr>
<table width="100%" border="0" style="font-size: 10px; border-collapse: separate; border-spacing: 2px;">
    <tbody>
        <tr>
            <td colspan="4"></td>
            <td colspan="2">Сумма (без скидки):</td>
            <td border="1" style="border:1px #000 solid" align="right"><?=$order->sumWithoutDiscount?> грн.</td>
        </tr>
        <?php
        if($order->sumDiscount > 0){
        ?>
        <tr>
            <td colspan="4"></td>
            <td colspan="2">
                Сумма скидки </td><td border="1" align="right" style="border:1px #000 solid">
                <?=$order->sumDiscount?> грн.
            </td>
        </tr>
        <?php
        }

        if($order->sumCustomerDiscount > 0){
        ?>
        <tr>
            <td colspan="4"></td>
            <td colspan="2">
                Дисконт (-2%):</td><td border="1" style="border:1px #000 solid" align="right">
                <?=$order->sumCustomerDiscount?> грн.
            </td>
        </tr>
        <?php
        }

        if($order->amountDeductedOrder > 0){
        ?>
        <tr>
            <td colspan="4"></td>
            <td colspan="2">
                Списано со счета :</td><td border="1" style="border:1px #000 solid" align="right">
                <?=$order->amountDeductedOrder?> грн.
            </td>
        </tr>
        <?php
        }
        ?>
        <tr>
            <td colspan="7">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td colspan="2"><b>Сумма к оплате</b>:</td>
            <td border="1" style="border:2px #000 solid" align="right"><?=$order->realSum?> грн.</td>
        </tr>
    </tbody>
</table>