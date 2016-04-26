<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 22.04.16
 * Time: 14:49
 */

use yii\helpers\Html;
use yii\widgets\ListView;

echo Html::tag('div',
	Html::tag('div', $model->who, ['class' => 'reviewer-name blue']).
	Html::tag('div', \Yii::$app->formatter->asDatetime($model->date, 'php:d F Y H:i'), ['class' => 'review-date']).
	Html::tag('div', $model->what).
	\kartik\tabs\TabsX::widget([
		'items' =>  [
			[
				'label'     =>  \Yii::t('shop', 'Ответить'),
				'content'   =>  $this->render('_comment_form',
					[
						'itemID'    =>  $model->goodID,
						'parent'    =>  $model->commentID,
						'type'      =>  2,
					]
				)
			],
			[
				'label'     =>  \Yii::t('shop', \Yii::t('shop', '{n, plural, =0{# ответов} =1{# ответ} few{#
									ответа}	many{# ответов} other{# ответ}}', [
					'n'	=>	sizeof($model->childs)
				])),
				'content'   =>  ListView::widget([
					'dataProvider'  =>  new \yii\data\ArrayDataProvider(['models' => $model->childs]),
					'summary'       =>  false,
					'itemView'      =>  function($model){
						return 	Html::tag('div', $model->who, ['class' => 'reviewer-name blue']).
						Html::tag('div', \Yii::$app->formatter->asDatetime($model->date, 'php:d F Y H:i'), ['class' => 'review-date']).
						Html::tag('div', $model->what);
					}
				])
			],
		]
	]),
	['class' => 'customer-review']);