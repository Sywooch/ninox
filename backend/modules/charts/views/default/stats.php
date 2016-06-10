<?php

use yii\bootstrap\Html;
/** @var array $possibleReports */

$this->title = 'Статистика и отчёты';

foreach($possibleReports as $key => $report){
    $reportContent = $report['label'];

    if(!empty($report) && array_key_exists('url', $report)){
        $reportContent = Html::a($reportContent, $report['url'], ['class' => 'list-group-item']);
    }else{
        $reportContent = Html::tag('li', $reportContent, ['class' => 'list-group-item']);
    }

    $possibleReports[$key] = $reportContent;
}

echo Html::tag('h1', $this->title, ['class' => 'page-header ']),
    Html::tag('h2', 'Отчёты'),
    Html::tag('ul', implode('', $possibleReports), ['class' => 'list-group']);