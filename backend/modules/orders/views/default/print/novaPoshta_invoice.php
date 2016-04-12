<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 05.04.16
 * Time: 18:06
 */

use yii\bootstrap\Html;

echo Html::tag('div',
    Html::button('Печатать экспресс-накладную', [
        'class' => 'btn btn-lg bth-default printEN',
        'ref'   =>  $invoice->deliveryReference
    ]).
    Html::button('Печатать маркировку отправления', [
        'class' => 'btn btn-lg bth-default printMark',
        'ref'   =>  $invoice->deliveryReference
    ])
),
    Html::tag('br').
    Html::tag('span', 'ТТН №'.$invoice->number, ['class' => 'label label-success']).'&nbsp;'.
    Html::tag('span', "Стоимость доставки {$invoice->deliveryCost} грн.", ['class' => 'label label-warning']).'&nbsp;'.
    Html::tag('span', 'Дата доставки: '.$invoice->deliveryEstimatedDate, ['class' => 'label label-info']);
