<?php
use yii\bootstrap\Html;
use yii\widgets\ListView;

$this->title = 'Мои отзывы';
$this->params['breadcrumbs'][] = $this->title;

echo Html::tag('div',
    $this->render('_account_menu').
    Html::tag('div',
        ListView::widget([
            'dataProvider'  =>  $reviews,
            'itemView'          =>  function($review){
                return Html::tag('div',
                    Html::tag('span', Html::a($review->goodID, '/tovar/-g'.$review->goodID).'&nbsp;'.Html::tag('small', \Yii::$app->formatter->asDate($review->date, 'php:d.m.Y H:i'))).
                    Html::tag('p', $review->what),
                    [
                        'class' =>  ($review->show == 1 ? 'success' : 'warning')
                    ]);
            }
        ]),
        [
        'class' =>  'user-data-content'
        ]),
    [
        'class' =>  'content'
    ]);