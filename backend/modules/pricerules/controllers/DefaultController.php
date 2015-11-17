<?php

namespace backend\modules\pricerules\controllers;

use backend\controllers\SiteController as Controller;
use common\models\Pricerule;
use yii\data\ActiveDataProvider;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        if(\Yii::$app->request->post()){
            $data = \Yii::$app->request->post("Pricerule");

            if(\Yii::$app->request->post("hasEditable") == 1){
                sort($data);
                $data = $data[0];
                $data['ID'] = \Yii::$app->request->post("editableKey");
            }

            $rule = Pricerule::findOne(['ID' => $data['ID']]);

            if(!$rule){
                return $this->run('site/error');
            }

            $rule->attributes = $data;

            $rule->save();

            if(\Yii::$app->request->post("hasEditable")){
                sort($data);
                return $data[0];
            }
        }

        return $this->render('index', [
            'dataProvider'  =>  new ActiveDataProvider([
                'query' =>  Pricerule::find(),
                'sort'  =>  [
                    'defaultOrder'  =>  'ID DESC'
                ],
                'pagination'    =>  [
                    'pageSize'  =>  20
                ]
            ])
        ]);
    }

    public function actionEdit($id = null){
        if($id == null && !empty(\Yii::$app->request->post("id"))){
            $id = \Yii::$app->request->post("id");
        }elseif($id == null){
            return $this->run('site/error');
        }

        $rule = Pricerule::findOne(['ID' => $id]);

        if(!$rule){
            return $this->run('site/error');
        }

        if(\Yii::$app->request->isAjax){
            return $this->renderAjax('_rule_edit', [
                'rule'  =>  $rule
            ]);
        }else{
            return $this->render('_rule_edit', [
                'rule'  =>  $rule
            ]);
        }
    }

    public function actionChangestate(){
        if(!\Yii::$app->request->isAjax){
            return $this->run('site/error');
        }

        $rule = Pricerule::findOne(['ID' => \Yii::$app->request->post("id")]);

        if(!$rule){
            return $this->run('site/error');
        }

        $rule->Enabled = $rule->Enabled == 1 ? 0 : 1;

        $rule->save();

        return $rule->Enabled;
    }
}
