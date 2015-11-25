<?php
use kartik\grid\GridView;
$this->title = 'Вопросы';
$this->params['breadcrumbs'][] = $this->title;

?>
<h1>Вопросы</h1>
<?php
$items = [];

foreach($questions as $question){
    $items[] = [
        'content' =>  $this->render('_question_item', [
            'question'  =>  $question
        ]),
        'options' =>  [
            'class' =>  'alert alert-success'
        ]
    ];
}?>
<?=\kartik\sortable\Sortable::widget([
    'items' =>  $items,
    'options'   =>  [
        'id'  =>  'feedback'
    ],
    'pluginEvents' => [
        'sortupdate' => 'function() { updSort(); }',
    ]
])?>
