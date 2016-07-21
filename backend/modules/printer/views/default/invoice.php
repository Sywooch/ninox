<?php

use yii\helpers\Html;

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

	/*@media print {
		.shopping table td.plateg > span, .shopping table td.manager > span, .shopping table td.delivery > span { margin-right: 0% !important; }
		.pageend { page-break-after: always; }
		.block, x:-moz-any-link { page-break-before: always; }
        .barcode {
            float: right;
	        margin-top: 2px;
        }
	}*/
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
$this->registerCssFile('/css/normalize.css');


//$this->registerCss($css2);

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
            'attribute' =>  'count',
            'value'     =>  function($model){
                return  $model->nalichie ? $model->count : 'нет в наличии';
            }
        ],
        [
            'header'    =>  'Цена (грн.)',
            'format'    =>  'html',
            'attribute' =>  'price',
            'value'     =>  function($model){
                return ($model->discountType != 0 ? Html::tag('s', $model->originalPrice).' ' : '').$model->price;
            }
        ],
        [
            'header'    =>  'Сумма',
            'value'     =>  function($model){
                return ($model->nalichie ? $model->count : 0) * $model->price;
            }
        ],
        [
            'header'    =>  'Скидка',
            'attribute' =>  'discountSize',
            'value'     =>  function($model){
                return $model->discountType != 0 ? $model->discountType == 2 ? $model->discountSize.'%' : ($model->discountSize * ($model->nalichie ? $model->count : 0)).' грн.' : '';
            }
        ]
    ]
]);

echo $this->render('_invoice_footer', [
    'order' =>  $order
]);