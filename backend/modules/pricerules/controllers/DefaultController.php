<?php

namespace backend\modules\pricerules\controllers;

use backend\controllers\SiteController as Controller;
use common\models\Category;
use common\models\Pricerule;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        if(\Yii::$app->request->post()){
            $id = \Yii::$app->request->post("ruleID");
            $name = \Yii::$app->request->post("ruleName");
            $rule = Pricerule::findOne(['ID' => $id]);
            $rule = empty($rule) ? new Pricerule() : $rule;

            if(empty($name) || !$rule->loadEntities(\Yii::$app->request->post())){
                return $this->run('site/error');
            }

            if(!$id){
                $rule->setAttributes([
                    'Name'      =>  $name,
                    'Enabled'   =>  0,
                    'Priority'  =>  Pricerule::find()->select('Priority')->max('Priority') + 1
                ]);
            }else{
                $rule->setAttributes([
                    'Name'      =>  $name,
                ]);
            }

            $rule->save();
        }

        return $this->render('index', [
            'rules' =>  Pricerule::find()->orderBy('Priority ASC')->all()
        ]);
    }

    public function actionUpdatesort(){
        if(!\Yii::$app->request->isAjax){
            //throw new \HttpRequestException("Данный метод можно вызвать только через Ajax!");
        }

        $rules = [];

        foreach(Pricerule::find()->orderBy('Priority ASC')->each() as $rule){
            $rules[$rule->ID] = $rule;
        }

        $sort = array_flip(\Yii::$app->request->post("data"));

        foreach($sort as $id => $pos){
            $rules[$id]->Priority = $pos;
            $rules[$id]->save();
        }
    }

    public function actionEdit($id = null){
        \Yii::$app->response->format = 'json';

        if($id == null && \Yii::$app->request->post("id") !== null){
            $id = \Yii::$app->request->post("id");
        }elseif($id == null){
            return $this->run('site/error');
        }

        $rule = Pricerule::findOne(['ID' => $id]);

        $termsDropdown = [];
        $typesDropdown = [];
        $categoriesDropdown = [];

        foreach(\backend\models\Pricerule::terms() as $possibleTerm){
            $termsDropdown[] = ['id' => $possibleTerm->attribute, 'text' => $possibleTerm->label];
            foreach($possibleTerm->possibleOperands as $possibleOperand){
                $typesDropdown[$possibleTerm->attribute][$possibleOperand] = $possibleOperand;
            }
        }

        $categories = Category::find()->with('translations')->all();

        foreach($categories as $category){
            $categoriesDropdown[] = ['id' => $category->Code, 'text' => $category->name];
        }

        if(!$rule){
            return [
                'name'      =>  '',
                'rule'  =>  [
                    'terms'     =>  [0 => ['term' => 'DocumentSum', 'value' => 0, 'type' => '>=']],
                    'actions'   =>  ['Discount' => 0, 'Type' => 2]
                ],
                'termsDropdown' =>  $termsDropdown,
                'typesDropdown' =>  $typesDropdown,
                'categoriesDropdown' =>  $categoriesDropdown,
            ];
        }

        return [
            'name'          =>  $rule->Name,
            'rule'          =>  $rule->asEntity,
            'termsDropdown' =>  $termsDropdown,
            'typesDropdown' =>  $typesDropdown,
            'categoriesDropdown' =>  $categoriesDropdown,
        ];
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

    /**
     * Ajax метод для работы с ценовыми правилами
     *
     * @return array
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionGetoperands(){
        if(!\Yii::$app->request->isAjax){
            throw new BadRequestHttpException();
        }

        \Yii::$app->response->format = 'json';

        foreach(\backend\models\Pricerule::terms() as $possibleTerm){
            $k = $possibleTerm->attribute;
            if($k == \Yii::$app->request->post('depdrop_parents')[0]){
                $typesDropdown = [];
                foreach($possibleTerm->possibleOperands as $possibleOperand){
                    $typesDropdown[] = ['id' => $possibleOperand, 'name' => $possibleOperand];
                }
                return ['output' => $typesDropdown, 'selected' => $possibleTerm->possibleOperands[0]];
            }
        }
        return ['output' => []];
    }
}
