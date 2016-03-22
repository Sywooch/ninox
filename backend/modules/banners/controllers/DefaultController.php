<?php

namespace backend\modules\banners\controllers;

use common\models\Banner;
use backend\models\BannersCategory;
use common\models\BannerType;
use yii\data\ActiveDataProvider;
use backend\controllers\SiteController as Controller;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        if(\Yii::$app->request->isAjax){
            try{
                return $this->run(\Yii::$app->request->post("action"));
            }catch (\ReflectionException $ex){
                return \Yii::$app->request->post();
            }
        }

        if(!empty(\Yii::$app->request->post())){
            $post = \Yii::$app->request->post();

            if(isset($post['BannerType'])){
                if(!empty($post['BannerType']['id'])){
                    $b = BannerType::findOne(['id' => $post['BannerType']['id']]);
                }else{
                    $b = new BannerType();
                }

                $b->load($post);
                $b->save();
            }

            if(isset($post['Banner'])){
                if(!empty($post['Banner']['id'])){
                    $b = Banner::findOne(['id' => $post['Banner']['id']]);
                }else{
                    $b = new Banner();
                }

                $b->load($post);
                $b->save();
            }
        }

        return $this->render('index', [
            'banners'   =>  new ActiveDataProvider([
                'query' =>  BannerType::find(),
                'pagination' => [
                    'pageSize' => 50,
                ],
            ])
        ]);
    }

    public function actionStats(){
        return $this->render('stats', [

        ]);
    }

    public function actionShowbanners($param){
        $bannersCategory = BannersCategory::findOne(['alias' => $param]);

        if(!$bannersCategory){
            throw new NotFoundHttpException("Такая категория баннеров не найдена!");
        }

        return $this->render('bannersList', [
            'banners'   =>  $bannersCategory->banners,
            'bannersCategory'   =>  $bannersCategory
        ]);
    }

    public function actionChangebannerstate(){
        $f = \Yii::$app->request->post("field");
        $f = $f == '' ? 'state' : $f;

        if(($f != 'state' && $f != 'deleted') || !\Yii::$app->request->isAjax){
            return false;
        }

        \Yii::$app->response->format = 'json';

        $m = Banner::findOne(['id' => \Yii::$app->request->post("banner")]);

        if($m){
            $m->$f = $m->$f == 0 ? 1 : 0;
            $m->save(false);

            return [
                'deleted'   =>  $m ? $m->deleted : 1,
                'state'     =>  $m ? $m->state : 0,
                'dateStart' =>  $m ? ($m->dateStart == '0000-00-00 00:00:00' ? '-' : $m->dateStart) : '',
                'dateEnd'   =>  $m ? ($m->dateEnd == '0000-00-00 00:00:00' ? '-' : $m->dateEnd) : ''
            ];
        }else{
            return [
                'deleted'   =>  1,
                'state'     =>  0,
                'dateStart' =>  '',
                'dateEnd'   =>  ''
            ];
        }
    }

    public function actionUpdatebannerssort(){
        if(!\Yii::$app->request->isAjax){
            return false;
        }

        $a = \Yii::$app->request->post("data");
        $a = array_reverse($a);
        $a = array_flip($a);

        $banners = Banner::findAll(['bannerTypeId' => \Yii::$app->request->post("category")]);

        foreach($banners as $banner){
            $banner->bannerOrder = $a[$banner->id];
            $banner->save(false);
        }

        \Yii::$app->response->format = 'json';
        return \Yii::$app->request->post();
    }
}
