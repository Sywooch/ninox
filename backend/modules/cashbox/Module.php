<?php

namespace backend\modules\cashbox;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\cashbox\controllers';

    public function init()
    {
        $this->layout = 'cashbox';

        parent::init();

        // custom initialization code goes here
    }
}
