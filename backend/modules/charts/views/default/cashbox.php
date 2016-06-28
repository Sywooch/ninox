<?php
$this->title = 'Отчёт по кассе';

$this->params['breadcrumbs'][] = $this->title;

echo \yii\helpers\Html::tag('h1', $this->title, ['class' => 'page-header col-xs-12', 'style' => 'margin-top: 0']);
?>
<button class="btn btn-default"><?=$report->year?> год</button>&nbsp;&nbsp;<button class="btn btn-default"><?=\Yii::$app->formatter->asDate($report->year.'-'.$report->month.'-01', 'LLLL')?></button>
    <br><br>
<?php
echo \kartik\grid\GridView::widget([
    'dataProvider'  =>  new \yii\data\ArrayDataProvider([
        'models'    =>  $report->stats,
    ]),
    'summary'       =>  false,
    'columns'       =>  [
        [
            'label'         =>  'Дата',
            'attribute'     =>  'date'
        ],
        [
            'label'         =>  'Сумма',
            'attribute'     =>  'sum',
            'value'         =>  function($model){
                return "$model->sum грн.";
            }
        ],
        [
            'label'         =>  'Траты',
            'attribute'     =>  'expenses',
            'value'         =>  function($model){
                return "$model->expenses грн.";
            }
        ],
        [
            'label'         =>  'Забрано с кассы',
            'attribute'     =>  'took',
            'value'         =>  function($model){
                return "$model->took грн.";
            }
        ],
        [
            'label'         =>  'Добавлено',
            'attribute'     =>  'added',
            'value'         =>  function($model){
                return "$model->added грн.";
            }
        ],
    ]
]);