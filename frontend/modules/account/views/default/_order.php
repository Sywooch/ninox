<?php

use yii\helpers\Html;

switch($model->status){
    case $model::STATUS_NOT_CALLED:
    case $model::STATUS_PROCESS:
    case $model::STATUS_NOT_PAYED:
    case $model::STATUS_WAIT_DELIVERY:
        $windowClass = 'order-waiting';
        break;
    case $model::STATUS_DELIVERED:
    case $model::STATUS_DONE:
        $windowClass = 'order-complete';
        break;
}

if($model->deleted){
    $windowClass = 'order-canceled';
}

?>

<div class="order <?=$windowClass?>">
    <div class="waiting spoiler-title">
        <i class="icon icon-arrow"></i>
        <div class="myriad">
            <?=$model->number?>
        </div>
        <div class="data semi">
            <?=\Yii::$app->formatter->asDatetime($model->added, 'php:d-m-Y')?>
        </div>
        <div class="payment semi">
            <?=$model->statusDescription?>
        </div>
        <div class="money semi">
            <?=\Yii::t('shop', '{sum} {sign}', [
                'sum'   =>  empty($model->actualAmount) ? $model->originalSum : $model->actualAmount,
                'sign'  =>  \Yii::$app->params['domainInfo']['currencyShortName']
            ])?>
        </div>
    </div>
    <div class="pr">
        <div class="print semi">
            <i class="icon icon-print"></i>
        </div>
        <div class="history semi">
            <a>История</a>
        </div>
        <div class="reorder semi">
            <a>Повторить заказ</a>
        </div>
    </div>
    <div class="spoiler-body" style="display: none;">
        <div class="body">
            <div class="seller">
            </div>
            <div class="sold-items">
                <?=\yii\widgets\ListView::widget([
                    'dataProvider'  =>  new \yii\data\ActiveDataProvider([
                        'query'     =>  $model->getItems(false),
                    ]),
                    'summary'   =>  false,
                    'itemView'  =>  '_items'
                ])?>
            </div>
            <div class="delivery">
                <div class="delivery-type">
                    Доставка (Курьер по вашему адресу)
                </div>
                <div class="delivery-coast">
                    50 грн
                </div>
            </div>
            <div class="total-price">
                <div class="title">
                    Итого к оплате:
                </div>
                <?=Html::tag('div', \Yii::t('shop', '{sum} {sign}', [
                    'sum'   =>  empty($model->actualAmount) ? $model->originalSum : $model->actualAmount,
                    'sign'  =>  \Yii::$app->params['domainInfo']['currencyShortName']
                ]), ['class' => 'sum'])?>
            </div>
        </div>
    </div>
</div>
