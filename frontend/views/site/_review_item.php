<?php
use yii\helpers\Html;

$reviewsHtml = '';

shuffle($reviews);
for($i = 0; $i <= 2; $i++){
	if(is_object($reviews[$i])){
		$reviewsHtml .= Html::tag('div',
			Html::tag('div',
				Html::tag('div', \Yii::$app->formatter->asDate($reviews[$i]->date, 'php: d F Y'), ['class' => 'review-date']).
				Html::tag('div', $reviews[$i]->name, ['class' => 'user-name uppercase']).
				Html::tag('div', $reviews[$i]->customerType, ['class' => 'user-occupation']),
			['class' => 'additional-data']).
			Html::tag('div', $reviews[$i]->review, ['class' => 'review-message']),
		['class' => 'review']);
	}
}

echo Html::tag('div',
		$reviewsHtml,
	['class' => 'reviews', 'data-label' => \Yii::t('shop', 'Отзывы о нас')]).
	Html::tag('div',
		Html::a(\Yii::t('shop', 'Посмотреть все отзывы'), '/otzyvy').
		Html::tag('input', '', ['type' => 'button', 'class' => 'yellow-button large-button', 'id' => 'sendReview', 'value' => \Yii::t('shop', 'Оставить отзыв')]),
	['class' => 'sender']);