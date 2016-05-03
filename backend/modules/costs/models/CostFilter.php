<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 10.04.16
 * Time: 18:16
 */

namespace backend\modules\costs\models;


use common\models\Cost;
use yii\data\ActiveDataProvider;

class CostFilter extends Cost
{

    public $dateFrom;
    public $dateTo;

    public function init(){
        $date = time() - (date('H') * 3600 + date('i') * 60 + date('s'));

        $this->dateTo = $date;
        $this->dateFrom = ($date - (date("j") - 1) * 86400);

        $this->dateTo = \Yii::$app->formatter->asDate($this->dateTo, 'php:Y-m-d');
        $this->dateFrom = \Yii::$app->formatter->asDate($this->dateFrom, 'php:Y-m-d');
    }

    public function search($params){
        $query = self::find();

        if(!empty($params['costId'])){
            $query->andWhere(['costId' => $params['costId']]);
        }

        $this->dateTo = \Yii::$app->formatter->asDate($this->dateTo, 'php:Y-m-d');
        $this->dateFrom = \Yii::$app->formatter->asDate($this->dateFrom, 'php:Y-m-d');

        $query->andWhere("`date` >= '{$this->dateFrom}'")->andWhere("`date` <= '{$this->dateTo}'");


        $dataProvider = new ActiveDataProvider([
            'query' =>  $query
        ]);

        $dataProvider->setSort([
            'defaultOrder' => [
                'id'	=>	SORT_DESC
            ],
            'attributes' => [
                'id' => [
                    'default' => SORT_DESC
                ],
                'date',
                'costSumm',
            ]
        ]);

        return $dataProvider;
    }

    protected function addCondition($query, $attribute, $partialMatch = false) {
        $value = $this->$attribute;
        if (trim($value) === '') {
            return;
        }

        if ($partialMatch) {
            $query->andWhere(['like', $attribute, $value.'%', false]);
        }else{
            $query->andWhere([$attribute => $value]);
        }
    }

}