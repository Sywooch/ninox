<?php


use yii\helpers\Html;

$delivery = 'delivery';
$paymentSum = 'paymentSum';

$css = <<<'CSS'
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
CSS;

$this->registerCss($css);

echo Html::tag('div',
    Html::tag('table',
        Html::tag('tr',
            Html::tag('td',
                Html::tag('div', '', ['id' => 'barcode'])
            ).
            Html::tag('td',
                Html::tag('span', $order->number, ['id' => 'code'])
            )
        ).
        Html::tag('tr',
            Html::tag('td',
                Html::tag('span', 'г. '.$order->deliveryCity, ['id' => 'city']),
                [
                    'colspan'   =>  2
                ]
            )
        ).
        Html::tag('tr',
            Html::tag('td',
                Html::tag('span', $order->deliveryRegion, ['class' => 'textcenter']),
                [
                    'colspan'   =>  2
                ]
            )
        ).
        Html::tag('tr',
            Html::tag('td',
                Html::tag('table',
                    Html::tag('tr',
                        Html::tag('td',
                            Html::tag('span', \Yii::$app->formatter->asPhone($order->customerPhone), ['class' => 'bold displayblock']).
                            $order->customerName.' '.$order->customerSurname,
                            [
                                'class' => 'border right bottom minwidth'
                            ]
                        ).
                        Html::tag('td',
                            $delivery
                            ,
                            [
                                'class' => 'border bottom',
                                'width' =>  '45%'
                            ])
                    ).
                    Html::tag('tr',
                        Html::tag('td',
                            Html::tag('span',
                                $paymentSum
                                ,
                                [
                                    'class' =>  'textbig'
                                ]
                            ),
                            [
                                'colspan'   =>  2
                            ]
                        )
                    ),
                    [
                        'class'         =>  'orderinfo',
                        'cellspacing'   =>  0,
                        'cellpadding'   =>  0,
                        'width'         =>  '100%'
                    ]
                ),
                [
                    'colspan'   =>  2
                ]
            )
        ).
        Html::tag('tr',
            Html::tag('td',
                Html::tag('table',
                    Html::tag('tr',
                        Html::tag('td',
                            Html::tag('span', 'Менеджер: ', ['class' => 'displayblock smalltext']).
                            Html::tag('span', $order->responsibleUserID, ['class' => 'displayblock smalltext'])
                        ).
                        Html::tag('td',
                            Html::tag('span', 'Дата поступления: '.\Yii::$app->formatter->asDatetime($order->added, 'php:d.m.Y H:i'), ['class' => 'displayblock smalltext']).
                            Html::tag('span', 'Дата сборки: '.\Yii::$app->formatter->asDatetime($order->doneDate, 'php:d.m.Y H:i'), ['class' => 'displayblock smalltext'])
                        )
                    ),
                    [
                        'cellspacing'   =>  0,
                        'cellpadding'   =>  0,
                        'width'         =>  '100%'
                    ]
                ),
                [
                    'colspan'   =>  2
                ]
            )
        )
    ),
    [
        'class' => 'barcode first'
    ]
),
Html::tag('div', '&nbsp;', ['class' => 'verticalseparator']),
Html::tag('div',
    Html::tag('table',
        Html::tag('tr',
            Html::tag('td',
                Html::tag('div', '', ['id' => 'barcodeTwo'])
            ).
            Html::tag('td',
                Html::tag('span', $order->number, ['id' => 'code'])
            )
        ).
        Html::tag('tr',
            Html::tag('td',
                Html::tag('span', 'г. '.$order->deliveryCity, ['id' => 'city']),
                [
                    'colspan'   =>  2
                ]
            )
        ).
        Html::tag('tr',
            Html::tag('td',
                Html::tag('span', $order->deliveryRegion, ['class' => 'textcenter']),
                [
                    'colspan'   =>  2
                ]
            )
        ).
        Html::tag('tr',
            Html::tag('td',
                Html::tag('table',
                    Html::tag('tr',
                        Html::tag('td',
                            Html::tag('span', \Yii::$app->formatter->asPhone($order->customerPhone), ['class' => 'bold displayblock']).
                            $order->customerName.' '.$order->customerSurname,
                            [
                                'class' => 'border right bottom minwidth'
                            ]
                        ).
                        Html::tag('td',
                            $delivery
                            ,
                            [
                                'class' => 'border bottom',
                                'width' =>  '45%'
                            ])
                    ).
                    Html::tag('tr',
                        Html::tag('td',
                            Html::tag('span',
                                $paymentSum
                                ,
                                [
                                    'class' =>  'textbig'
                                ]
                            ),
                            [
                                'colspan'   =>  2
                            ]
                        )
                    ),
                    [
                        'class'         =>  'orderinfo',
                        'cellspacing'   =>  0,
                        'cellpadding'   =>  0,
                        'width'         =>  '100%'
                    ]
                ),
                [
                    'colspan'   =>  2
                ]
            )
        ).
        Html::tag('tr',
            Html::tag('td',
                Html::tag('table',
                    Html::tag('tr',
                        Html::tag('td',
                            Html::tag('span', 'Менеджер: ', ['class' => 'displayblock smalltext']).
                            Html::tag('span', $order->responsibleUserID, ['class' => 'displayblock smalltext'])
                        ).
                        Html::tag('td',
                            Html::tag('span', 'Дата поступления: '.\Yii::$app->formatter->asDatetime($order->added, 'php:d.m.Y H:i'), ['class' => 'displayblock smalltext']).
                            Html::tag('span', 'Дата сборки: '.\Yii::$app->formatter->asDatetime($order->doneDate, 'php:d.m.Y H:i'), ['class' => 'displayblock smalltext'])
                        )
                    ),
                    [
                        'cellspacing'   =>  0,
                        'cellpadding'   =>  0,
                        'width'         =>  '100%'
                    ]
                ),
                [
                    'colspan'   =>  2
                ]
            )
        )
    ),
    [
        'class' => 'barcode'
    ]
),
\barcode\barcode\BarcodeGenerator::widget([
    'elementId' =>  'barcode',
    'type'      =>  'code39',
    'value'     =>  $order->id,
    'settings'  =>  [
        'output'=>  'bmp'
    ]
]),
\barcode\barcode\BarcodeGenerator::widget([
    'elementId' =>  'barcodeTwo',
    'type'      =>  'code39',
    'value'     =>  $order->id,
    'settings'  =>  [
        'output'=>  'bmp'
    ]
]);