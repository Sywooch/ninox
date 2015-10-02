<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 20.05.15
 * Time: 16:20
 */

namespace common\models;


use yii\data\ActiveDataProvider;

class CustomerSearch extends Customer{

    public function search($params){

        $query = Customer::find();

        $dataProvider = new ActiveDataProvider([
            'query' =>  $query,
            'pagination'    =>  [
                'pageSize'  =>  isset($params['pageSize']) ? $params['pageSize'] : 20
            ]
        ]);

        $dataProvider->setSort([
            'defaultOrder' => [
                'ID'	=>	SORT_DESC
            ],
            'attributes' => [
                'ID' => [
                    'default' => SORT_DESC
                ],
                'UserRealTime'
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->addCondition($query, 'ID');
        $this->addCondition($query, 'Company', true);
        $this->addCondition($query, 'Phone');
        $this->addCondition($query, 'City', true);
        $this->addCondition($query, 'CardNumber');
        $this->addCondition($query, 'eMail', true);
        $this->addCondition($query, 'money');

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
            [['Company', 'Phone', 'City', 'CardNumber', 'eMail', 'money', 'ID'], 'safe']
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