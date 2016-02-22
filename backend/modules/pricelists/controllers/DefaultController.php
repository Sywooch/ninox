<?php

namespace backend\modules\pricelists\controllers;

use backend\modules\pricelists\models\PriceListForm;
use common\models\Category;
use common\models\PriceListFeed;
use yii\base\ErrorException;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `pricelists` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $pricelists = new ActiveDataProvider([
            'query' =>  PriceListFeed::find()
        ]);

        return $this->render('index', [
            'pricelists'   =>  $pricelists
        ]);
    }

    public function actionCategoriestree(){
        if(!\Yii::$app->request->isAjax){
            throw new \BadMethodCallException("Этот метод доступен только через ajax!");
        }

        \Yii::$app->response->format = 'json';

        $categories = Category::find()->all();

        return $this->buildTree($categories);
    }

    public function buildTree(&$items, $parent = ''){
        $branch = [];

        foreach($items as $item){
            if(substr($item->Code, 0, -3) == $parent){
                $branch[$item->Code]['title'] = $item->Name;
                $branch[$item->Code]['key'] = $item->ID;
                $subitems = $this->buildTree($items, $item->Code);

                if($subitems){
                    $branch[$item->Code]['children'] = $subitems;
                    $branch[$item->Code]['folder'] = true;
                }
            }
        }

        sort($branch);

        return $branch;
    }

    public function actionAdd(){
        \Yii::$app->response->format = 'json';

        if(\Yii::$app->request->post("PriceListForm")){
            $priceListForm = new PriceListForm();

            $priceListForm->load(\Yii::$app->request->post());

            if(!$priceListForm->save()){
                //throw new ErrorException("Случилась ошибка при сохранении прайс-листа!");
            }

            return true;
        }
    }

    public function actionRemove(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException();
        }

        $list = PriceListFeed::findOne(\Yii::$app->request->post("priceListID"));

        if(!$list){
            throw new NotFoundHttpException();
        }

        if($list->delete()){
            return true;
        }else{
            throw new \ErrorException();
        }
    }
}
