<?php

use yii\bootstrap\Html;

echo Html::tag('div', Html::tag('div', Html::a('К заказам', '/', [
    'class' =>  'yellow-button small-button button',
]).
    Html::tag('div',
        Html::tag('span', $order->number),
        ['class' => 'order-number']).Html::button('Сохранить', [
        'type'  =>  'submit',
        'class' =>  'green-button small-button button',
        'id'    =>  'submit'
    ]),
    ['class' => 'header']).
    \yii\widgets\ListView::widget([
        'dataProvider'  =>  new \yii\data\ActiveDataProvider([
            'query' =>  $order->getItems(false)
        ]),
        'summary'   =>  false,
        'itemOptions'   =>  [
            'class'     =>  'typical-block',
        ],
        'itemView'  =>  function($model){
            return $this->render('_sborka_item', [
                'item'  =>  $model
            ]);
        }
    ]),
    ['class' => 'sborka']);

