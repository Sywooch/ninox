<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 06.06.16
 * Time: 15:42
 */

namespace backend\models;


use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

class CustomersOrdersSearch extends Customer
{

    public function search($params){

        $query = Customer::find()->with('orders')->leftJoin('history', '`history`.`customerID` = `partners`.`ID`')->andWhere(['`partners`.`Deleted`' => 0, '`history`.`deleted`' => 0]);

        $dataProvider = new ActiveDataProvider([
            'query' =>  $query,
            'pagination'    =>  [
                'pageSize'  =>  array_key_exists('pageSize', $params) ? $params['pageSize'] : 50
            ]
        ]);

        /*$dataProvider->setSort([
            'defaultOrder' => [
                'registrationTime'	=>	SORT_DESC
            ],
            'attributes' => [
                'registrationTime' => [
                    'default' => SORT_DESC
                ],
                'UserRealTime'
            ]
        ]);*/

        $query->andWhere(['`history`.`moneyConfirmed`' => 1]);

        $query->groupBy('`history`.`customerID`')->orderBy('SUM(`history`.`actualAmount`) DESC');

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->addCondition($query, 'ID');
        $this->addCondition($query, 'Company', true);
        $this->addCondition($query, 'phone', true);
        $this->addCondition($query, 'City', true);
        $this->addCondition($query, 'cardNumber');
        $this->addCondition($query, 'email', true);
        $this->addCondition($query, 'money');

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

        return $dataProvider;
    }

    public function rules()
    {
        return [
            [['Company', 'phone', 'City', 'cardNumber', 'email', 'money', 'ID'], 'safe']
        ];
    }

    /**
     * @param ActiveRecord $query
     * @param string $attribute
     * @param bool $partialMatch
     */
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