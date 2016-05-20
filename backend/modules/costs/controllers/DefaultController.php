<?php

namespace backend\modules\costs\controllers;

use backend\modules\costs\models\CostFilter;
use backend\modules\costs\models\CostForm;
use backend\controllers\SiteController as Controller;
use common\models\CostsType;

/**
 * Default controller for the `costs` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $costFilter = new CostFilter();

        if(\Yii::$app->request->get("dateFrom")){
            $costFilter->dateFrom = \Yii::$app->request->get("dateFrom");
        }

        if(\Yii::$app->request->get("dateTo")){
            $costFilter->dateFrom = \Yii::$app->request->get("dateTo");
        }

        $costForm = new CostForm();

        if(\Yii::$app->request->post("CostForm")){
            $costForm->load(\Yii::$app->request->post());

            if($costForm->save()){

            }
        }

        return $this->render('index', [
            'types'     =>  CostsType::find()->all(),
            'costFilter'=>  $costFilter,
            'costForm'  =>  $costForm
        ]);
    }
}
