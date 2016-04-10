<?php
echo \yii\widgets\ListView::widget([
    'dataProvider'  =>  new \yii\data\ActiveDataProvider([
        'query' =>  $order->getItems(false)
    ]),
    'summary'   =>  false,
    'itemView'  =>  function($model){
        return $this->render('_sborka_item', [
            'item'  =>  $model
        ]);
    }
]);