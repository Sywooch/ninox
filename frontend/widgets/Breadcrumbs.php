<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 03.04.16
 * Time: 16:23
 */

namespace frontend\widgets;

use yii\helpers\Html;
use yii\helpers\Url;

class Breadcrumbs extends \yii\widgets\Breadcrumbs
{
	public $homeLink = [
		'label'     =>  '',
		'class'     =>  'icon-home'
	];

	public function init(){
		parent::init();
		$this->homeLink['url'] = Url::to(['/', 'language' => \Yii::$app->language]);
		$this->homeLink['template'] = Html::tag('li', '{link}', [
			'itemscope' =>  '',
			'itemtype'  =>  'http://data-vocabulary.org/Breadcrumb'
		]);

		$this->itemTemplate = $this->activeItemTemplate = Html::tag('li', '{link}', [
			'itemscope' =>  '',
			'itemtype'  =>  'http://data-vocabulary.org/Breadcrumb',
			'class'     =>  'icon-arrow-right'
		]);
	}
}