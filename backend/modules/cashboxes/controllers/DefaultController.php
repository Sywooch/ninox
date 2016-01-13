<?php

namespace backend\modules\cashboxes\controllers;

use backend\controllers\SiteController as Controller;
use common\models\Cashbox;

/**
 * Default controller for the `cashboxes` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionNew(){
        return $this->render('new');
    }

    public function actionView($param){
        $cashbox = Cashbox::findOne($param);

        return $this->render('view', [
            'cashbox'   =>  $cashbox
        ]);
    }
}
