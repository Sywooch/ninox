<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 12/10/2015
 * Time: 2:36 PM
 */

use yii\bootstrap\Html;

echo Html::tag('div', Html::tag('div',
        Html::img(\Yii::$app->params['frontend'].'/img/catalog/'.$model->photo), [
            'class' => 'image'
        ]).
    Html::tag('div',
        Html::tag('div',
            Html::a($model->name, '/tovar/'.$model->good->link.'-g'.$model->itemID).
            Html::tag('span', $model->good->Code, ['class' => 'order-number']),
            [
                'class' =>  'order-profile'
            ]
        ).
        Html::tag('div',
            Html::tag('div', \Yii::t('shop', '{sum} {sign}', ['sum' => $model->price, 'sign' => \Yii::$app->params['domainInfo']['currencyShortName']]), ['class' => 'one-price']).
            Html::tag('div', \Yii::t('shop', '{sum} {sign}', ['sum' => ($model->price * $model->count), 'sign' => \Yii::$app->params['domainInfo']['currencyShortName']]), ['class' => 'sum-price']).
            Html::tag('div', \Yii::t('shop', '{count} шт.', ['count' => $model->count]), ['class' => 'sum']),
            [
                'class' => 'price'
            ]
        ),
        [
            'class' => 'data'
        ]
    ), ['class' => 'items']);