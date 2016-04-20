<?php


use yii\helpers\Html;

$css = <<<'CSS'
.barcodeCode {
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
div.horizontalseparator {
    width: 100%;
    height: 15mm;
    display: block;
    clear: both;
    border-top: 1px dotted #000000;
}
div.barcode.first {
    margin-left: 10mm;
    margin-right: -1mm;
}
div.barcode {
    margin-left: 2mm;
    margin-top: 10mm;
    padding: 0;
    height: 85mm;
    width: 98mm;
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
    margin-right: 5px;
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
    div.horizontalseparator {
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
        margin-right: 15px;
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
CSS;

$this->registerCss($css);

echo Html::tag('div',
    $this->render('_last_page_part', [
        'order' =>  $order
    ]),
    [
        'class' => 'barcode first'
    ]
),
Html::tag('div', '&nbsp;', ['class' => 'verticalseparator']),
Html::tag('div',
    $this->render('_last_page_part', [
        'order' =>  $order
    ]),
    [
        'class' => 'barcode'
    ]
),
Html::tag('div', '', ['class' => 'horizontalseparator']);