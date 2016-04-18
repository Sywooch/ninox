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

        if(!empty($params["ordersSource"])){
	        switch($params["ordersSource"]){
		        case 'all':
			        break;
		        case 'market':
			        $query->andWhere(['sourceType' => History::SOURCETYPE_SHOP]);
			        break;
		        case 'deleted':
			        $query->andWhere('deleted != 0');
			        break;
		        case 'internet':
		        default:
			        $query->andWhere(['sourceType' => History::SOURCETYPE_INTERNET]);
			        break;
	        }
        }

	    if(!empty($params["smartFilter"])){
		    switch($params["smartFilter"]){
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
	    }

        if(!empty($params["showDates"]) || empty($params['HistorySearch'])){
            $date = time() - (date('H') * 3600 + date('i') * 60 + date('s'));

            $params['showDates'] = empty($params['showDates']) ? 'today' : $params['showDates'];

            switch($params["showDates"]){
                case 'yesterday':
                    $query->andWhere('added <= '.$date.' AND added >= '.($date - 86400));
                    break;
                case 'thisweek':
                    $query->andWhere('added >= '.($date - (date("N") - 1) * 86400));
                    break;
                case 'thismonth':
                    \Yii::trace($date);
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

        $params['ordersStatus'] = isset($params['ordersStatus']) ? $params['ordersStatus'] : 'new';

        switch($params['ordersStatus']){
            case 'all':
                break;
            case 'done':
                $query->andWhere(['status' => self::STATUS_DONE]);
                break;
            case 'new':
            default:
                $query->andWhere(['status' => self::STATUS_NOT_CALLED]);
                break;
        }

        if(!empty($params["showDeleted"]) && (!empty($params['ordersSource']) && $params["ordersSource"] != 'deleted')){
            $query->andWhere('deleted = 0');
        }

        if (!($this->load($params) && $this->validate())) {
            return $onlyQuery ? $query : $dataProvider;
        }

        $this->addCondition($query, 'id');
        $this->addCondition($query, 'number');
        $this->addCondition($query, 'customerPhone', true);
        $this->addCondition($query, 'customerSurname', true);
        $this->addCondition($query, 'customerEmail', true);
        $this->addCondition($query, 'deliveryCity', true);
        $this->addCondition($query, 'nakladna', true);
        $this->addCondition($query, 'actualAmount');
        $this->addCondition($query, 'responsibleUserID');

        return $onlyQuery ? $query : $dataProvider;
    }

    public function rules()
    {
        return [
            [['id', 'number', 'customerPhone', 'customerSurname', 'customerEmail', 'deliveryCity', 'nakladna', 'actualAmount', 'responsibleUserID'], 'safe']
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