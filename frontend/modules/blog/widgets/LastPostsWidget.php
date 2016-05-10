<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 05.05.16
 * Time: 18:35
 */

namespace frontend\modules\blog\widgets;


use common\models\BlogArticle;
use yii\base\Widget;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;

class LastPostsWidget extends Widget
{

    public function run(){
        $articles = new ActiveDataProvider([
            'query' =>  BlogArticle::find()->limit(5),
            'pagination'    =>  [
                'pageSize'  =>  6
            ],
            'sort'  =>  [
                'defaultOrder'  =>  [
                    'date'  =>  SORT_DESC
                ]
            ]
        ]);

        return ListView::widget([
            'dataProvider'  =>  $articles,
            'itemView'      =>  function($model){
                echo Html::tag('p', Html::a($model->title, '/blog/'.$model->link));
            },
            'layout'        =>  '{items}'
        ]);
    }

}