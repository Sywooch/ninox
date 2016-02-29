<?php
use kartik\tabs\TabsX;

echo \yii\bootstrap\Html::tag('h2', 'Введите информацию о клиенте');

echo TabsX::widget([
    'items' =>  [
        [
            'label'     =>  'Существующий',
            'content'   =>  $this->render('_customer_modal_search')
        ],
        [
            'label' =>  'Новый',
            'content'   =>  $this->render('_customer_modal_new')
        ]
    ],
    'position'  =>  TabsX::ALIGN_LEFT
]);

?>