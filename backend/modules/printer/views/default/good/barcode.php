<?php
use yii\helpers\Html;

$css = <<<'CSS'
.good-barcode{
    width: 30mm;
    height: 20mm;
}

.good-barcode hr{
    margin: 0;
}

.barcode-title{
    font-size: 2mm;
    text-align: center;
    width: 100%;
    font-family: Arial,serif;
    display: inline-block;
}

.barcode-article{
    font-size: 3mm;
    text-align: center;
    width: 100%;
    display: inline-block;
    margin-top: -2mm;
    position: relative;
    font-family: Arial,serif;
}

#barcodeTarget{
    position: relative;
}

#barcodeTarget div:last-of-type{
    position: absolute;
    bottom: 0;
    width: auto !important;
    left: 50%;
    margin-left: -29px;
    padding: 0 5px;
}

CSS;

$this->registerCss($css);

echo Html::tag('div',
    Html::tag('span', $good->name, ['class' => 'barcode-title']).
    Html::tag('hr').
    Html::tag('span', "Арт. {$good->Code}", ['class' => 'barcode-article']).
    Html::tag('div', '', ['id' => 'barcodeTarget', 'style' => 'margin: 0px auto']).
    \barcode\barcode\BarcodeGenerator::widget([
        'elementId' =>  'barcodeTarget',
        'type'      =>  'ean8',
        'value'     =>  $good->Code,
        'settings'  =>  [
            'output'=>  'css',
        ]
    ]),
    [
        'class' =>  'good-barcode'
    ]);