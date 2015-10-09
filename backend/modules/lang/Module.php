<?php

namespace backend\modules\lang;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\lang\controllers';
	public $layout = "main";

	public function init()
	{
		$this->layoutPath = \Yii::$app->getModule('admin')->getLayoutPath();

		parent::init();

		// custom initialization code goes here
	}
}
