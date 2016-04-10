<?php

namespace backend\modules\costs\controllers;

use backend\modules\costs\models\CostFilter;
use common\models\CostsType;
use yii\web\Controller;

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

        return $this->render('index', [
            'types'     =>  CostsType::find()->all(),
            'costFilter'=>  $costFilter
        ]);
    }
}
