<?php
use yii\helpers\Html;

$questionsHtml = '';

shuffle($questions);
for($i = 0; $i <= 2; $i++){
	if(is_object($questions[$i])){
		$questionsHtml .= Html::tag('div',
			Html::tag('div',
				Html::tag('div', \Yii::t('shop', 'Вопрос').':', ['class' => 'question-label']).
				Html::tag('div', \Yii::$app->formatter->asDate($questions[$i]->date_question, 'php:d F Y'), ['class' => 'question-date']).
				Html::tag('div', $questions[$i]->question, ['class' => 'question-msg']),
			['class' => 'question']).
			Html::tag('div',
				Html::tag('div', \Yii::t('shop', 'Ответ').':', ['class' => 'answer-label']).
				Html::tag('div', \Yii::$app->formatter->asDate($questions[$i]->date_answer, 'php:d F Y'), ['class' => 'answer-date']).
				Html::tag('div', $questions[$i]->answer, ['class' => 'answer-msg']),
			['class' => 'answer']),
		['class' => 'question-answer']);
	}
}

echo Html::tag('div',
		$questionsHtml,
	['class' => 'questions', 'data-label' => \Yii::t('shop', 'Вопросы и ответы')]).
	Html::tag('div',
		Html::a(\Yii::t('shop', 'Посмотреть все вопросы'), '/voprosy-i-otvety').
		Html::tag('input', '', ['type' => 'button', 'class' => 'yellow-button large-button', 'id' => 'addQuestion', 'value' => \Yii::t('shop', 'Задать вопрос')]),
	['class' => 'sender']);