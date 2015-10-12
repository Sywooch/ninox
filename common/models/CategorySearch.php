<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 13.05.15
 * Time: 15:52
 */

namespace common\models;


use yii\data\ActiveDataProvider;

class CategorySearch extends Category{

    public function search($params){
        if(empty($params) || empty($params['len'])){
            return [];
        }

        $sf = \Yii::$app->request->get("smartfilter");

        $from = [Category::tableName().' a'];

        $query = Category::find();

        if($sf){
            $query->select('a.*')->addSelect(['SUBSTR(`a`.`Code`, \'1\', \''.$params['len'].'\') AS `codeAlias`']);
            $query->leftJoin('goods b', 'a.ID = b.GroupID')
                ->groupBy('codeAlias');

            if($params['len'] != 3){
                $query->andWhere('LENGTH(a.Code) > '.($params['len'] - 3));
            }

            switch($sf){
                case 'disabled':
                    $query->andWhere(['b.show_img' => 0]);
                    break;
                case 'enabled':
                    $query->andWhere(['b.show_img' => 1]);
                    break;
            }
        }else{
            $query->andWhere(['LENGTH(`a`.`Code`)' => $params['len']]);
            $query->groupBy('a.Code');
        }

        if($params['len'] != 3 && !empty($params['cat'])){
            $query->andWhere(['like', 'a.Code', $params['cat'].'%', false]);
        }

        $query->from($from);
        $query->orderBy('a.listorder, a.ID ASC');

        return $query->all();
    }

}