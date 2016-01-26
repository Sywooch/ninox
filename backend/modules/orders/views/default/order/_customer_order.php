<?php
use yii\helpers\Html;

$orderText = 'Заказ №'.$order->number.' от '.\Yii::$app->formatter->asDate($order->added, 'php:d.m.Y').' на сумму '.$order->actualAmount.' грн.';

if($order->id == $nowOrder){
    $orderText = Html::tag('span', $orderText.' (текущий)', [
        'class' =>  'text-muted'
    ]);
}else{
    $orderClasses = [];

    if($order->deleted != 0){
        $orderClasses[] = 'text-danger';
    }elseif($order->done == 1){
        $orderClasses[] = 'text-success';
    }

    $orderText = Html::a($orderText, \yii\helpers\Url::to('/orders/showorder/'.$order->id), [
        'class' =>  implode(' ', $orderClasses)
    ]);
}

echo $orderText;