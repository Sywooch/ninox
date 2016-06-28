<?php
use yii\helpers\Html;

foreach($videos as $video){
	echo Html::tag('iframe', '', [
		'class'             =>  'item-video',
		'width'             =>  '560',
		'height'            =>  '315',
		'src'               =>  '//www.youtube.com/embed/'.$video->video,
		'frameborder'       =>  '0',
		'allowfullscreen'   =>  true,
	]);
}