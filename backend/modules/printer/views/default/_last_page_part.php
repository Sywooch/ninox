<?php
use yii\bootstrap\Html;

$delivery = $payment = '';

$paymentSum = empty($order->actualAmount) ? $order->realSum : $order->actualAmount;

$barcodeID = \Yii::$app->security->generateRandomString(8);

$responsibleUser = $order->responsibleUser;

if(empty($responsibleUser)){
    $responsibleUser = new \common\models\Siteuser();
}

switch($order->deliveryType){
    case 1:
        $delivery = Html::tag('span', 'Адресная доставка', ['class' => 'bold smalltext', 'style' => 'display: block']).
            Html::tag('span', $order->deliveryInfo);
        break;
    case 2:
        $delivery = Html::tag('span', "Склад № {$order->deliveryInfo}", ['class' => 'bold']);
        break;
    case 3:
        $delivery = Html::tag('span', "Самовывоз", ['class' => 'bold']);
        break;
}

switch($order->paymentType){
    case 1:
        $payment = 'Наложеный платёж';
        break;
    case 2:
        $payment = 'Оценочная стоимость';
        break;
    case 3:
        $payment = 'стоимость';
        break;
}

echo Html::tag('table',
    Html::tag('tr',
        Html::tag('td',
            Html::tag('div', '', ['id' => 'barcode_'.$barcodeID, 'class' => 'barcodeCode'])
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
                            $payment
                            ,
                            [
                                'class' =>  'textbig'
                            ]
                        ).
                        Html::tag('span', round($paymentSum, 0).' грн.', ['class' => 'bold']),
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
                        Html::tag('span', $responsibleUser->name, ['class' => 'displayblock smalltext'])
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
\barcode\barcode\BarcodeGenerator::widget([
    'elementId' =>  'barcode_'.$barcodeID,
    'type'      =>  'code39',
    'value'     =>  $order->id,
    'settings'  =>  [
        'output'=>  'bmp'
    ]
]);