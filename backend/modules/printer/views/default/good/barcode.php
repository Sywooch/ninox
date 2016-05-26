<?php
use yii\helpers\Html;

$css = <<<'CSS'
.good-barcode{
    width: 26mm;
    height: 16mm;
}

.good-barcode hr{
    margin: -2px;
    margin-bottom: -7px;
    background: none; 
    border-top: 1px solid #000; 
    border-bottom: none;
}

.barcode-title{
    font-size: 1.6mm;
    text-align: center;
    width: 100%;
    font-family: Arial,serif;
    display: inline-block;
}

.barcode-article{
    font-size: 2.2mm;
    text-align: center;
    width: 100%;
    display: inline-block;
    margin-top: -2.5mm;
    position: relative;
    font-family: Arial,serif;
}

#barcodeTarget{
    position: relative;
    overflow: hidden !important;
}

#barcodeTarget div:last-of-type{
    position: absolute;
    bottom: 0;
    width: auto !important;
    left: 50%;
    margin-left: -24px;
    padding: 0 5px;
    font-size: 2.2mm !important;
}

CSS;

$this->registerCss($css);

echo Html::tag('div',
    Html::tag('div',
        Html::tag('span', $good->name, ['class' => 'barcode-title']).
        Html::tag('hr').
        Html::tag('span', "Арт. {$good->Code}\\{$good->wholesalePrice}", ['class' => 'barcode-article']).
        Html::tag('div', '', ['id' => 'barcodeTarget', 'style' => 'margin: 0px auto; max-height: 30px']).
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
        ]),
    [
        'style' =>  'padding: 2px'
    ]);