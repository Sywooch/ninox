<?php
$dayofweek = [
    "Вс",
    "Пн",
    "Вт",
    "Ср",
    "Чт",
    "Пт",
    "Сб"
];
?>
<span style="font-size:20px;">Заказ №<?=$order->number?> от <?=\Yii::$app->formatter->asDatetime($order->added, 'php:d.m.Y - H:i')?> <span style="font-size:18px;"><?=$dayofweek[date("w", $order->added)]?></span></span>
<div id="barcodeTarget" class="barcode" style="float: right; margin-top: 0px; height: 50px;"></div>
<?=\barcode\barcode\BarcodeGenerator::widget([
    'elementId' =>  'barcodeTarget',
    'type'      =>  'code39',
    'value'     =>  $order->id,
    'settings'  =>  [
        'output'=>  'bmp'
    ]
])?>
<span style="font-size: 10px; display:block">
    <?=$order->customerSurname?> <?=$order->customerName?>&nbsp;&nbsp;&nbsp;
    <?=$order->customerPhone?><br>
    г. <?=$order->deliveryCity?>, <?=$order->deliveryRegion?>, <?=($order->deliveryType == 1 ? '<span class="bold smalltext" style="display: block">Адресная доставка</span><span>'.$order->deliveryAddress.'</span>' : ($order->deliveryType == 2 ? '<span class="bold">Склад №'.$order->deliveryInfo.'</span>' : ($order->deliveryType == 4 ? '<span class="bold">Самовывоз</span>' : '')))?>
</span>
<hr>