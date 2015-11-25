<?php
use bobroid\remodal\Remodal;
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\Html;

$this->title = 'Обратная связь';
$this->params['breadcrumbs'][] = $this->title;

?>
<br>
<br>
<?php
$items = [];

$feedbacks =array();
foreach($feedbacks as $feedback){
    $items[] = [
        'content' =>  $this->render('_feedback_item', [
            'feedback'  =>  $feedback
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
