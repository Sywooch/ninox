<?php

namespace app\modules\tasks;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\tasks\controllers';
    public $layout = "main";

    public function init()
    {
        $this->layoutPath = \Yii::$app->getModule('admin')->getLayoutPath();
        parent::init();

        // custom initialization code goes here
    }
}
