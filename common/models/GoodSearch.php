<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 13.05.15
 * Time: 12:02
 */

namespace common\models;


use yii\data\ActiveDataProvider;

class GoodSearch extends Good{

    public function search($params){

        $query = Good::find();

        if(!empty($params['category'])){
            if(is_array($params['category'])){
                $query->andWhere(['in', 'GroupID', $params['category']]);
            }else{
                $query->andWhere(['GroupID' => $params['category']]);
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' =>  $query,
            'pagination'    =>  [
                'pageSize'  =>  isset($params['pageSize']) ? $params['pageSize'] : 20
            ],
        ]);

        if(\Yii::$app->request->get("smartfilter") != ''){
            switch(\Yii::$app->request->get("smartfilter")){
                case 'disabled':
                    $query->andWhere(['show_img' => 0]);
                    break;
                case 'enabled':
                    $query->andWhere(['show_img' => 1]);
                    break;
            }
        }

        return $dataProvider;
    }

    public function rules()
    {
        return [
            [['type', 'direction', 'status', 'responsible_user_id'], 'safe']
        ];
    }

    protected function addCondition($query, $attribute, $partialMatch = false) {
        $value = $this->$attribute;
        if (trim($value) === '') {
            return;
        }

        if ($partialMatch) {
            $query->andWhere(['like', $attribute, $value]);
        }else{
            $query->andWhere([$attribute => $value]);
        }
    }

}