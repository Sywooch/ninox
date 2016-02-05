<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 20.05.15
 * Time: 16:20
 */

namespace backend\models;

use yii\data\ActiveDataProvider;

class HistorySearch extends History{

    public function search($params, $onlyQuery = false){
        $query = History::find();

        $dataProvider = new ActiveDataProvider([
            'query' =>  $query,
            'pagination'    =>  [
                'pageSize'  =>  isset($params['pageSize']) ? $params['pageSize'] : 50,
            ]
        ]);

        $dataProvider->setSort([
            'defaultOrder' => [
                'id'	=>	SORT_DESC
            ],
            'attributes' => [
                'id' => [
                    'default' => SORT_DESC
                ],
                'added',
                'customerPhone',
                'deliveryCity',
                'actualAmount',
                'responsibleUserID'
            ]
        ]);

        if(\Yii::$app->request->get("ordersSource") != ''){
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
        }

	    if(\Yii::$app->request->get("smartFilter")){
		    switch(\Yii::$app->request->get("smartFilter")){
			    case 'shipping':
					$query->andWhere(
						[
							'and',
							'done = 1',
							['or',
								'nakladna = \'\'',
								'nakladna = \'-\''],
							['or',
								['and',
									['in',
										'paymentType',
										['2', '3', '4']
									],
									['moneyConfirmed' => 1]
		                        ],
								['and',
									['in',
										'paymentType',
										['1', '5']
									],
									['moneyConfirmed' => 0]
		                        ]
							]
						]);
					$query->andWhere('deliveryType != 4 AND actualAmount > 0');
				    break;
			    default:
				    break;
		    }
	    }elseif(empty($params['HistorySearch'])){
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
        }

        if(!\Yii::$app->request->get("showDeleted") && \Yii::$app->request->get("ordersSource") != 'deleted'){
            $query->andWhere('deleted = 0');
        }

        if (!($this->load($params) && $this->validate())) {
            return $onlyQuery ? $query : $dataProvider;
        }

        $this->addCondition($query, 'id');
        $this->addCondition($query, 'customerPhone', true);
        $this->addCondition($query, 'deliveryCity', true);
        $this->addCondition($query, 'responsibleUserID', true);

        return $onlyQuery ? $query : $dataProvider;
    }

    public function rules()
    {
        return [
            [['id', 'customerPhone', 'deliveryCity', 'responsibleUserID'], 'safe']
        ];
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