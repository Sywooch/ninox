<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 06.05.16
 * Time: 17:25
 */

namespace backend\models;


use yii\data\ActiveDataProvider;

class GoodSearch extends \common\models\GoodSearch
{
    
    public function search($params, $onlyQuery = false){

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
                'pageSize'  =>  isset($params['per-page']) ? $params['per-page'] : (isset($params['view']) && $params['view'] == 'list' ? 100 : 20)
            ]
        ]);

        if(\Yii::$app->request->get("smartfilter") != ''){
            switch(\Yii::$app->request->get("smartfilter")){
                case 'disabled':
                    $query->andWhere(['enabled' => 0]);
                    break;
                case 'enabled':
                    $query->andWhere(['enabled' => 1]);
                    break;
            }
        }

        return $onlyQuery ? $query : $dataProvider;
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