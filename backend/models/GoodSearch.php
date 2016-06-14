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
                'pageSize'  =>  array_key_exists('per-page', $params) ? $params['per-page'] : (array_key_exists('view', $params) && $params['view'] == 'list' ? 100 : 20)
            ]
        ]);

        if(!empty($params['smartFilter'])){
            $query->joinWith('translations');
            switch($params['smartFilter']){
                case 'disabled':
                    $query->andWhere(['enabled' => 0, 'language' => \Yii::$app->language]);
                    break;
                case 'enabled':
                    $query->andWhere(['enabled' => 1, 'language' => \Yii::$app->language]);
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