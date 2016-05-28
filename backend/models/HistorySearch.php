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

    public function search($params, $onlyQuery = false, array $ignoreFilters = []){
        $query = History::find();

        $dataProvider = new ActiveDataProvider([
            'query' =>  $query,
            'pagination'    =>  [
                'pageSize'  =>  array_key_exists('pageSize', $params) ? $params['pageSize'] : 50,
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

        if(empty($params['ordersSource'])){
            $params['ordersSource'] = null;
        }elseif($params['ordersSource'] == 'search'){
            $ignoreFilters['ordersSource'] = true;
            $ignoreFilters['ordersStatus'] = true;
        }

        if(array_key_exists('showDates', $params) && empty($params['showDates'] && empty(\Yii::$app->request->get('ordersStatus')))){
            $params['showDates'] = 'alltime';
        }

        if(!array_key_exists('ordersSource', $ignoreFilters) && array_key_exists('ordersSource', $params) && !empty($params['ordersSource'])){
	        switch($params['ordersSource']){
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

        if(!array_key_exists('sourceID', $ignoreFilters) && !empty($params['sourceID'])){
	        $query->andWhere(['orderSource' => $params['sourceID']]);
        }

	    if(!array_key_exists('smartFilter', $ignoreFilters) && !empty($params['smartFilter'])){
		    switch($params['smartFilter']){
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

        if((!empty($params['showDates'])  && !array_key_exists('showDates', $ignoreFilters)) || (empty($params['HistorySearch'])  && !array_key_exists('HistorySearch', $ignoreFilters))){
            $date = time() - (date('H') * 3600 + date('i') * 60 + date('s'));


            $params['showDates'] = empty($params['showDates']) ? 'alltime' : $params['showDates'];

            switch($params['showDates']){
                case 'yesterday':
                    $query->andWhere('added <= '.$date.' AND added >= '.($date - 86400));
                    break;
                case 'thisweek':
                    $query->andWhere('added >= '.($date - (date('N') - 1) * 86400));
                    break;
                case 'thismonth':
                    $query->andWhere('added >= '.($date - (date('j') - 1) * 86400));
                    break;
                case 'today':
                    $query->andWhere('added >= '.$date);
                    break;
                case 'alltime':
                default:
                    break;
            }
        }

        if(!array_key_exists('ordersStatus', $ignoreFilters) && $params['ordersSource'] != 'search'){
            $params['ordersStatus'] = !empty($params['ordersStatus']) ? $params['ordersStatus'] : 'new';

            switch($params['ordersStatus']){
                case self::STATUS_WAIT_DELIVERY:
                case 'delivery':
                    $query
	                    ->andWhere(['status' => self::STATUS_WAIT_DELIVERY])
                        ->andWhere(['in', 'deliveryType', [1, 2]])
                        ->andWhere(['deleted' => 0]);
                    break;
                case 'notPayedOnCard':
                    $query->andWhere(['moneyConfirmed' => 0, 'paymentType' => 2])
                        ->andWhere('`actualAmount` != \'0\'');
                    break;
                case 'done':
                case self::STATUS_DONE:
                    $query->andWhere(['or', ['status' => self::STATUS_DONE], ['status' => self::STATUS_DELIVERED]]);
                    break;
                case 'new':
                    $query->andWhere(['or', ['status' => self::STATUS_NOT_CALLED], ['status' => self::STATUS_PROCESS], ['status' => self::STATUS_NOT_PAYED], ['status' => self::STATUS_WAIT_DELIVERY]]);
                    break;
                case 'all':
                default:
                    break;
            }
        }

        if((!array_key_exists('showDeleted', $ignoreFilters) && empty($params['showDeleted'])) && $params['ordersSource'] != 'search'){
            $query->andWhere('deleted = 0');
        }elseif(!empty($params['ordersSource']) && $params['ordersSource'] == 'deleted'){
            $query->andWhere('deleted = 1');
        }

        if(array_key_exists('responsibleUser', $params)){
            $query->andWhere(['responsibleUserID' => $params['responsibleUser']]);
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