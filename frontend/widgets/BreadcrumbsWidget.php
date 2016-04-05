<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 03.04.16
 * Time: 16:23
 */

namespace frontend\widgets;


use yii\base\Widget;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

class BreadcrumbsWidget extends Widget
{
	public $links = [];

	public function run(){
		return Breadcrumbs::widget([
			'homeLink'              =>  [
				'template'  =>  Html::tag('li', '{link}', [
					'itemscope' =>  '',
					'itemtype'  =>  'http://data-vocabulary.org/Breadcrumb'
				]),
				'label'     =>  '',
				'url'       =>  '/',
				'class'     =>  'icon-home'
			],
			'itemTemplate'          =>  Html::tag('li', '{link}', [
				'itemscope' =>  '',
				'itemtype'  =>  'http://data-vocabulary.org/Breadcrumb',
				'class'     =>  'icon-arrow-right'
			]),
			'activeItemTemplate'    =>  Html::tag('li', '{link}', [
				'itemscope' =>  '',
				'itemtype'  =>  'http://data-vocabulary.org/Breadcrumb',
				'class'     =>  'icon-arrow-right'
			]),
			'links'                 =>  $this->links
		]);
	}
}