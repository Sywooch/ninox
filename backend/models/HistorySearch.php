<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 20.05.15
 * Time: 16:20
 */

namespace backend\models;


use common\models\History;
use yii\data\ActiveDataProvider;

class HistorySearch extends \common\models\History{

    public function search($params, $query = false){

        $query = History::find();

        $dataProvider = new ActiveDataProvider([
            'query' =>  $query,
            'pagination'    =>  [
                'pageSize'  =>  isset($params['pageSize']) ? $params['pageSize'] : 50
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
                'added',
                'customerPhone',
                'DeliveryCity',
                'actualAmount'
            ]
        ]);

        if(empty(\Yii::$app->request->get("ordersSource")) && empty(\Yii::$app->request->get("showDates")) && empty(\Yii::$app->request->get("showDeleted")) && empty(\Yii::$app->request->get("responsibleUser"))){
            if (!($this->load($params) && $this->validate())) {
                return $query ? $query : $dataProvider;
            }
        }

        $this->addCondition($query, 'ID');
        $this->addCondition($query, 'customerPhone', true);
        $this->addCondition($query, 'DeliveryCity', true);

        switch(\Yii::$app->request->get("ordersSource")){
            case 'all':
                break;
            case 'market':
                $query->andWhere(['deliveryType' => 5, 'paymentType' =>  6]);
                break;
            case 'deleted':
                $query->andWhere('deleted != 0');
                break;
            case 'shop':
            default:
                $query->andWhere('deliveryType != 5 AND paymentType != 6');
                break;
        }

        $date = time() - (date('H') * 3600 + date('i') * 60 + date('s'));

        switch(\Yii::$app->request->get("showDates")){
            case 'yesterday':
                $query->andWhere('added <= '.$date.' AND added >= '.($date - 86400));
                break;
            case 'thisweek':
                $query->andWhere('added >= '.($date - (date("N") - 1) * 86400));
                break;
            case 'thismonth':
                $query->andWhere('added >= '.($date - (date("j") - 1) * 86400));
                break;
            case 'alltime':
                break;
            case 'today':
            default:
                $query->andWhere('added >= '.$date);
                break;
        }

        if(!\Yii::$app->request->get("showDeleted") && \Yii::$app->request->get("ordersSource") != 'deleted'){
            $query->andWhere('deleted = 0');
        }

        if(\Yii::$app->request->get("responsibleUser")){
            $query->andWhere(['responsibleUserID' => \Yii::$app->request->get("responsibleUser")]);
        }

        //$this->addCondition($query, 'City', true);
       // $this->addCondition($query, 'CardNumber');
        //$this->addCondition($query, 'eMail', true);
        //$this->addCondition($query, 'money');

        /*if(\Yii::$app->request->get("smartfilter") != ''){
            switch(\Yii::$app->request->get("smartfilter")){
                case 'disabled':
                    $query->andWhere(['show_img' => 0]);
                    break;
                case 'enabled':
                    $query->andWhere(['show_img' => 1]);
                    break;
            }
        }*/

        return $query ? $query : $dataProvider;
    }

    public function rules()
    {
        return [
            [['ID', 'customerPhone', 'DeliveryCity'], 'safe']
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