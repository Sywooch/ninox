<?php

namespace backend\modules\pricerules\controllers;

use backend\controllers\SiteController as Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
