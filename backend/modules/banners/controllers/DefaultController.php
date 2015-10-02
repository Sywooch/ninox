<?php

namespace app\modules\banners\controllers;

use app\models\Banner;
use app\models\BannerType;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex($p1 = '', $p2 = '')
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

        if($p1 == "" && $p2 == ""){
            return $this->runAction('actionindex');
        }else{
            if($p2 != ""){
                return $this->runAction($p1, [
                    'param' =>  $p2
                ]);
            }else{
                return $this->runAction($p1);
            }
        }
    }

    public function actionActionindex(){
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
        $bannersCategory = BannerType::findOne(['id' => $param]);
        $banners = Banner::find()->where(['bannerTypeId' => $bannersCategory->id])->orderBy('bannerOrder DESC')->all();

        if($bannersCategory){
            return $this->render('bannersList', [
                'banners'   =>  $banners,
                'bannersCategory'   =>  $bannersCategory
            ]);
        }else{

        }
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
