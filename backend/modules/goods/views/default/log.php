<?php
    $this->title = 'Лог изменений';
    $this->params['breadcrumbs'][] = [
        'url'   =>  '/goods',
        'label' =>  'Категории',
    ];
    $this->params['breadcrumbs'][] = $this->title;
?>
<h1>Лог изменений</h1>
<?=\kartik\grid\GridView::widget([
    'dataProvider'  =>  $dataProvider,
    'columns'       =>  [
        'old_value',
        'new_value',
        'field',
        'user_id',
        'model_id',
    ],
])?>