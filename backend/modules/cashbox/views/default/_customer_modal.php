<?php
use kartik\tabs\TabsX;
?>
<h2>Введите информацию о клиенте</h2>
<?=TabsX::widget([
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
])?>