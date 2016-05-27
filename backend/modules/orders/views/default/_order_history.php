<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 22.05.16
 * Time: 15:53
 */
use yii\helpers\Html;

$history = [];

$history[] = Html::tag('span', Html::tag('b', 'Поступил').'&nbsp;'.\Yii::$app->formatter->asDatetime($order->added, 'dd MMMM yyyy, HH:mm'));

if(!empty($order->doneDate)){
    $history[] = Html::tag('span', Html::tag('b', 'Выполнено').'&nbsp;'.\Yii::$app->formatter->asDatetime($order->doneDate, 'dd MMMM yyyy, HH:mm'));
}

if(!empty($order->moneyConfirmedDate)){
    $history[] = Html::tag('span', Html::tag('b', 'Оплачен').'&nbsp;'.\Yii::$app->formatter->asDatetime($order->moneyConfirmedDate, 'dd MMMM yyyy, HH:mm'));
}

if(!empty($order->sendDate) && $order->sendDate != '0000-00-00 00:00:00'){
    $history[] = Html::tag('span', Html::tag('b', 'Отправлен').'&nbsp;'.\Yii::$app->formatter->asDatetime($order->sendDate, 'dd MMMM yyyy, HH:mm'));
}

?>
<div class="col-xs-12 col-sm-6 col-md-8 block-span" style="text-align: left">
    <?php
    echo
        Html::tag('b', 'История заказа'),
        Html::tag('div', '', ['class' => 'blue-line']),
        implode('', $history);

    if(!empty($order->nakladna)){
        echo
            Html::tag('div', '', ['class' => 'blue-line']),
            Html::tag('span', Html::tag('b', 'ТТН&nbsp;').$order->nakladna).
            Html::tag('span', 'SMS', [
                'class' =>  'label label-info',
                'style' =>  'display: inline-block; border-radius: 10px; line-height: normal;'
            ]);
    }
    ?>
</div>
<div class="col-xs-6 col-md-4" style="text-align: left">
    <b>Статус смс</b>
    <div class="blue-line"></div>
    <ul>
        <li><span>Не дозвонились</span></li>
        <li><span>О готовности</span></li>
        <li><span>Для оплаты</span></li>
        <li><span>С номером ТТН</span></li>
    </ul>
</div>
