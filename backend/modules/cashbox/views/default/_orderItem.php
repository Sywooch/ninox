<?php
use rmrevin\yii\fontawesome\FA;
?>
<tr data-key="<?=$model->ID?>">
    <td class="removeGood cashbox-table-column">
        <?=FA::icon('times')->size(FA::SIZE_LARGE)?>
    </td>
    <td class="counter cashbox-table-column2x" align="center"></td>
    <td class="cashbox-table-column2x" align="center"><?=$model->Code?></td>
    <td class="tdborder">
        <?=$model->Name?>
    </td>
    <td class="cashbox-table-column3x" align="center">
        <input class="changeqtty" data-id="2028757" value="1">
    </td>
    <td class="tdborder" width="90px" align="center"><span><?=$model->PriceOut1?></span> грн.</td>
    <td class="tdborder" width="130px" align="center"><span><?=$model->PriceOut1?></span> грн.</td>
    <td class="tdborder">-0%</td>
</tr>