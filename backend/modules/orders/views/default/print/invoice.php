<?php

$css = <<<'STYLE'
	body { background: none; color: #000;}
	table.printOrder { width: 90%; margin: 80px; color: #616161; }
	td.print_head { border-bottom: 1px solid #9a9a9a; }
	.leader { font-size: 26px; padding-top: 10px; height: 30px; }
	.tovars2 { border: none;}
	.tovars_img2 {
		display: block;
		border:1px solid #555555;
		width: 165px;
		height: 123px;}
	.tovars_img2 img{
		width: 165px;
		height: 123px;}
	.shopping table td {text-align:center;}
	.shopping table td.city { font-size:30px; text-decoration:underline; font-weight: bold; }
	.shopping table td.oblast { font-size:12px;}
	.shopping table td.nova_poshta { font-size:18px; }
	.shopping table td.name { font-size:18px; font-weight:bold;}
	.shopping table td.telefon { font-size:14px; }
	.shopping table td.plateg, .shopping table td.manager, .shopping table td.delivery { font-size:14px; font-weight:bold;white-space: nowrap; text-align: right; }
	.shopping table td.plateg > span > span, .shopping table td.manager > span > span, .shopping table td.delivery > span > span { padding-right: 5px; }
	.shopping table td.plateg > span, .shopping table td.manager > span, .shopping table td.delivery > span { margin-right: 33%; }
	.shopping table td.manager { border-bottom: 1px solid #9a9a9a; padding-bottom: 5px; }
	.shopping table td.plateg { border-top: 1px solid #9a9a9a; padding-top: 5px; }
	.shopping table td.zakaznumber { font-size: 60px; }
	.shopping table td.plateg img, .shopping table td.manager img, .shopping table td.delivery img{ vertical-align:middle; }
	.shopping input, .shopping textarea, .shopping select { border:none; }
	hr{
		margin: 5px 0;
	}
	.print_excel{
		font-size: 10px;
		width: 100%;
	}
	.print_excel td{
		padding: 1px;
	}

    .print_excel {
        border-collapse:collapse;
        font-size:12px;
        font-family:Arial,Helvetica,sans-serif;
    }
    .print_excel td,.print_excel th {
        border:1px solid #000;
        padding:3px;
    }

	@media print {
		.shopping table td.plateg > span, .shopping table td.manager > span, .shopping table td.delivery > span { margin-right: 0% !important; }
		.pageend { page-break-after: always; }
		.block, x:-moz-any-link { page-break-before: always; }
        .barcode {
            float: right;
	        margin-top: 2px;
        }
	}
    .barcode {
        float: right;
	    margin-top: 2px;
    }
STYLE;

$css2 = <<<'STYLE'
div.barcode img {
    margin-right: 4mm;
}
div.verticalseparator {
    width: 1mm;
    margin-left: 4mm;
    display: block;
    float: left;
    height: 95mm;
    border-left: 1px dotted #000000;
}
div.gorizontalseparator {
    width: 100%;
    height: 15mm;
    display: block;
    clear: both;
    border-top: 1px dotted #000000;
}
div.barcode.first {
    margin-left: 10mm;
}
div.barcode {
    margin-left: 2mm;
    margin-top: 10mm;
    padding: 0;
    height: 85mm;
    width: 92mm;
    float: left;
    overflow: hidden;
    display: inline-block !important;
    font-size: 8pt;
    font-family: Arial, sans-serif;
}
div.barcode span#city {
    width: 100%;
    display: block;
    font-size: 2.5em;
    text-align: center;
    font-family: Arial, sans-serif;
}
span.textcenter {
    text-align: center;
    display: block;
}
table.orderinfo {
    border: 3px solid #000000;
    margin-top: 2mm;
}
table.orderinfo td {
    padding: 4mm;
}
.bold {
    font-weight: bold;
    font-size: 1.5em;
}
.smalltext {
    font-size: 1em;
}
.border.right {
    border-right: 3px solid #000000;
}
.border.bottom {
    border-bottom: 3px solid #000000;
}
.displayblock {
    display: block;
}
.textbig {
    font-size: 1.5em;
}
.minwidth {
    min-width: 52%;
}
div.barcode span#code {
    width: 100%;
    display: block;
    overflow: hidden;
    font-size: 4em;
    font-weight: bold;
    text-align: center;
    font-family: Arial, sans-serif;
}
@media print {
    @page {
        size: auto;
        margin: 0mm;
        padding: 0mm;
        width: 210mm;
        height: 297mm;
    }
    * {
        margin: 0mm;
        padding: 0mm;
    }
    div {
        display: none;
    }
    body {
        margin: 0mm;
        padding: 4mm 0 0 0;
        width: 210mm;
        display: block !important;

    }
    div.barcode img {
        margin-right: 4mm;
    }
    div.verticalseparator {
        width: 1mm;
        margin-left: 4mm;
        display: block;
        float: left;
        height: 95mm;
        border-left: 3px dotted #000000;
    }
    div.gorizontalseparator {
        width: 100%;
        height: 15mm;
        display: block;
        clear: both;
        border-top: 3px dotted #000000;
    }
    div.barcode.first {
        margin-left: 10mm;
    }
    div.barcode {
        margin-left: 2mm;
        margin-top: 10mm;
        padding: 0;
        height: 85mm;
        width: 92mm;
        float: left;
        overflow: hidden;
        display: inline-block !important;
        font-size: 8pt;
        font-family: Arial, sans-serif;
    }
    .minwidth {
        min-width: 52%;
    }
    div.barcode span#city {
        width: 100%;
        display: block;
        font-size: 2.5em;
        text-align: center;
        font-family: Arial, sans-serif;
    }
    span.textcenter {
        text-align: center;
        display: block;
    }
    table.orderinfo {
        border: 3px solid #000000;
        margin-top: 2mm;
    }
    table.orderinfo td {
        padding: 4mm;
    }
    .bold {
        font-weight: bold;
        font-size: 1.5em;
    }
    .smalltext {
        font-size: 1em;
    }
    .border.right {
        border-right: 3px solid #000000;
    }
    .border.bottom {
        border-bottom: 3px solid #000000;
    }
    .displayblock {
        display: block;
    }
    .textbig {
        font-size: 1.5em;
    }
    div.barcode span#code {
        width: 100%;
        display: block;
        overflow: hidden;
        font-size: 4em;
        font-weight: bold;
        text-align: center;
        font-family: Arial, sans-serif;
    }
}
STYLE;

$js = <<<'SCRIPT'
    function PrintWindow(){
        window.print();
        //CheckWindowState();
    }

    function CheckWindowState(){
        if(document.readyState != "complete"){
            setTimeout(function(){CheckWindowState();}, 2000);
        }
    }

    PrintWindow();
SCRIPT;

$this->registerJs($js);
$this->registerCss($css);
$this->registerCss($css2);
$this->registerCssFile('/css/normalize.css');

echo $this->render('_invoice_header');

echo $this->render('_invoice_order_info', [
    'order' =>  $order
]);

echo \yii\grid\GridView::widget([
    'dataProvider'  =>  $orderItems,
    'summary'       =>  false,
    'tableOptions'       =>  [
        'class'     =>  'print_excel'
    ],
    'columns'       =>  [
        [
            'header'    =>  '№',
            'class'     =>  \yii\grid\SerialColumn::className()
        ],
        [
            'header'    =>  'Код товара',
            'value'     =>  function($model) use(&$goods){
                return $goods[$model->itemID]->Code;
            },
        ],
        [
            'header'    =>  'Наименование',
            'attribute' =>  'name'
        ],
        [
            'header'    =>  'Кол.',
            'attribute' =>  'count'
        ],
        [
            'header'    =>  'Цена (грн.)',
            'attribute' =>  'price'
        ],
        [
            'header'    =>  'Сумма',
            'value'     =>  function($model){
                return $model->count * $model->price;
            }
        ],
        [
            'header'    =>  'Скидка',
            'attribute' =>  'discountSize',
            'value'     =>  function($model){
                return $model->discountSize.($model->discountType != 0 ? $model->discountType == 2 ? '%' : ' грн.' : '');
            }
        ]
    ]
]);

echo $this->render('_invoice_footer', [
    'items' =>  $orderItems->getModels()
]);

/*if($act == 'printWithImages' || $act == 'printOrder'){
    echo $this->render('_invoice_head', [
        'order' =>  $order
    ]);
}

if($act == 'printNakladna'){
    echo $this->render('_invoice_head2', [
        'order' =>  $order,
        'goods' =>  $orderItems
    ]);
}

if($act == 'printLastPage' || $act == 'printWithImages' || $act == 'printOrder'){
    $this->registerCss($css2);

    $totalsumm_all = 0;
    foreach($order['goods'] as $good){
        $i++;
        if ($good['nezakaz']==0 && $good['nalichie']!=0){
            $price_sum = ($good['PriceOut'] * $good['Qtty']);
            $totalsumm_all += $price_sum;
            $sumToDiscount += $good['discountPrice'] ? 0 : $price_sum;
        }
    }

    if($totalsumm_all > 800 && $order['kupon'] == '1600271014' && $order['displayorder'] < 1415059200){
        $coupon_proc = 50;
    }

    if($partner['CardNumber'] <> 0){
        $msg_discont_skidka = 2;
        $discount_proc = round(($msg_discont_skidka * $sumToDiscount / 100), 2);
    }else{
        $discount_proc = 0;
    }

    if($order['plateg'] == $paymentsDetails['2']['name']){
        $bankPercent = round((($totalsumm_all - $discount_proc - $coupon_proc - $order['amountDeductedOrder']) * 0.01) + 2, 2);
    }else{
        $bankPercent = 0;
    }

    echo $this->render('_customer_part');
    ?>

        <div class="barcode first">
            <table cellspacing="0" cellpadding="0">
                <tr>
                    <td><img src="data:image/jpg;base64,<?=''//$barcode['img']?>" /></td>
                    <td><span id="code"><?=$code?></span></td>
                </tr>
                <tr>
                    <td colspan="2"><span id="city">г. <?=$orderNew['city']?></span></td>
                </tr>
                <tr>
                    <td colspan="2"><span class="textcenter"><?=$orderNew['oblast']?></span></td>
                </tr>
                <tr>
                    <table class="orderinfo" cellspacing="0" cellpadding="0" width="100%">
                        <tr>
                            <td class="border right bottom minwidth" width="55%">
                                <span class="bold displayblock"><?=substr($order['phone'],0,2)?> <?=substr($order['phone'],2,3)?> <?=substr($order['phone'],5,3)?> <?=substr($order['phone'],8,2)?> <?=substr($order['phone'],10,2)?></span>
                                <?=$orderNew['name']?> <?=$orderNew['surname']?>
                            </td>
                            <td class="border bottom" width="45%">
                                <?=($order['dostavka']==1 ? '<span class="bold smalltext" style="display: block">Адресная доставка</span><span>'.$order['adress'].'</span>' :($order['dostavka']==2 ? '<span class="bold">Склад №'.$orderNew['novaposhta'].'</span>' : ($order['dostavka']==4 ? '<span class="bold">Самовывоз</span>' : '')))?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <span class="textbig"><?=($orderNew['plateg']==1 ? 'Наложенный платеж' : ($orderNew['plateg']==2 ? 'Оценочная стоимость' : ($orderNew['plateg']==5 ? 'Стоимость' : 'Наложенный платеж')) )?></span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="bold"><?=round(($totalsumm_all + $bankPercent - $discount_proc - $coupon_proc - $order['amountDeductedOrder']), 0)?> грн.</span>
                            </td>
                        </tr>
                    </table>
                </tr>
                <tr>
                    <table cellspacing="0" cellpadding="10" width="100%">
                        <tr>
                            <td>
                                <span class="displayblock smalltext">Менеджер:</span>
                                <span class="displayblock smalltext"><?=$orderNew['sborshik']?></span>
                            </td>
                            <td>
                                <span class="displayblock smalltext">Дата поступления: <?php echo date('d.m.Y H:i', $orderNew['displayorder']); ?></span>
                                <span class="displayblock smalltext">Дата сборки: <?php echo date('d.m.Y H:i'); ?></span>
                            </td>
                        </tr>
                    </table>
                </tr>
            </table>
        </div>
        <div class="verticalseparator">&nbsp;</div>
        <div class="barcode">
            <table cellspacing="0" cellpadding="0">
                <tr>
                    <td><img src="data:image/jpg;base64,<?=$barcode['img']?>" /></td>
                    <td><span id="code"><?=$code?></span></td>
                </tr>
                <tr>
                    <td colspan="2"><span id="city">г. <?=$orderNew['city']?></span></td>
                </tr>
                <tr>
                    <td colspan="2"><span class="textcenter"><?=$orderNew['oblast']?></span></td>
                </tr>
                <tr>
                    <table class="orderinfo" cellspacing="0" cellpadding="0" width="100%">
                        <tr>
                            <td class="border right bottom minwidth" width="55%">
                                <span class="bold displayblock"><?=substr($order['phone'],0,2)?> <?=substr($order['phone'],2,3)?> <?=substr($order['phone'],5,3)?> <?=substr($order['phone'],8,2)?> <?=substr($order['phone'],10,2)?></span>
                                <?=$orderNew['name']?> <?=$orderNew['surname']?>
                            </td>
                            <td class="border bottom" width="45%">
                                <?=($order['dostavka']==1 ? '<span class="bold smalltext" style="display: block">Адресная доставка</span><span>'.$order['adress'].'</span>' :($order['dostavka']==2 ? '<span class="bold">Склад №'.$orderNew['novaposhta'].'</span>' : ($order['dostavka']==4 ? '<span class="bold">Самовывоз</span>' : '')))?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <span class="textbig"><?=($orderNew['plateg']==1 ? 'Наложенный платеж' : ($orderNew['plateg']==2 ? 'Оценочная стоимость' : ($orderNew['plateg']==5 ? 'Стоимость' : 'Наложенный платеж')) )?></span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="bold"><?=round(($totalsumm_all + $bankPercent - $discount_proc - $coupon_proc - $order['amountDeductedOrder']), 0)?> грн.</span>
                            </td>
                        </tr>
                    </table>
                </tr>
                <tr>
                    <table cellspacing="0" cellpadding="10" width="100%">
                        <tr>
                            <td>
                                <span class="displayblock smalltext">Менеджер:</span>
                                <span class="displayblock smalltext"><?=$orderNew['sborshik']?></span>
                            </td>
                            <td>
                                <span class="displayblock smalltext">Дата поступления: <?php echo date('d.m.Y H:i', $orderNew['displayorder']); ?></span>
                                <span class="displayblock smalltext">Дата сборки: <?php echo date('d.m.Y H:i'); ?></span>
                            </td>
                        </tr>
                    </table>
                </tr>
            </table>
        </div>
        <div class="gorizontalseparator">&nbsp;</div>
    <?php
    }
*/


