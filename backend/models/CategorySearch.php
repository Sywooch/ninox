<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 02.03.16
 * Time: 13:30
 */

namespace backend\models;


use common\models\CategoryTranslation;
use common\models\GoodTranslation;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class CategorySearch extends Category
{

    /*public function rules()
    {
        return [
            [['id', 'number', 'customerPhone', 'customerSurname', 'customerEmail', 'deliveryCity', 'nakladna', 'actualAmount', 'responsibleUserID'], 'safe']
        ];
    }*/

    public function search($params, $onlyQuery = false){
        $category = !empty($params['category']) ? $params['category'] : '';
        $categoryLength = $category != '' ? (strlen($category) + 3) : 3;

        $query = Category::find()
            ->select(["SUBSTR(`goodsgroups`.`Code`, '1', '{$categoryLength}') AS `codeAlias`"])
            ->leftJoin('goods', '`goods`.`GroupID` = `goodsgroups`.`ID`')
            ->leftJoin(CategoryTranslation::tableName(), ['`category_translations`.`ID`' => '`goodsgroups`.`ID`']);

        if($categoryLength > 3){
            $tLen = $categoryLength - 3; //когда нельзя сделать эту операцию в скобке :с
            $query->andWhere(['like', 'goodsgroups.Code', $category.'%', false])
                ->andWhere("LENGTH(`goodsgroups`.`Code`) > '{$tLen}'");
        }

        if(array_key_exists('smartFilter', $params)){
            if(in_array($params['smartFilter'], ['enabled', 'disabled'])){
                $query->leftJoin(GoodTranslation::tableName(), '`item_translations`.`ID` = `goods`.`ID`')->andWhere(['`item_translations`.`language`' => \Yii::$app->language]);
            }

            switch($params['smartFilter']){
                case 'enabled':
                    $query->andWhere(['`item_translations`.`enabled`' => 1]);
                    break;
                case 'disabled':
                    $query->andWhere(['`item_translations`.`enabled`' => 0]);
                    break;
                case 'onSale':
                    $query->andWhere("`goods`.`discountType` != '0'");
                    break;
                case 'withoutPhoto':
                    $query->leftJoin('dopfoto', '`dopfoto`.`itemid` = `goods`.`ID`')
                        ->having('COUNT(`dopfoto`.`itemid`) < 1');
                    $query->addGroupBy('`dopfoto`.`itemid`');
                    break;
                case 'withoutPrices':
                    $query->andWhere("`goods`.`PriceOut1` <= '0' OR `goods`.`PriceOut2` <= '0'");
                    break;
                case 'withoutAttributes':
                    //$query->andWhere("`goods`.`PriceOut1` <= '0' OR `goods`.`PriceOut2` <= '0'");
                    break;
            }
        }

        $query->addGroupBy('codeAlias')
            ->orderBy('`category_translations`.`sequence`')
            ->addOrderBy('`goodsgroups`.`ID`');

        $query = Category::find()
            ->select('`a`.*')
            ->from(['`goodsgroups` `a`', '('.$query->prepare(\Yii::$app->db->queryBuilder)->createCommand()->rawSql.') as `tmp`'])
            ->where('`a`.`Code` = `tmp`.`codeAlias`');

        $dataProvider = new ActiveDataProvider([
            'query' =>  $query,
            'pagination'    =>  [
                'pageSize'  =>  0
            ]
        ]);

        /*$dataProvider->setSort([
            'defaultOrder' => [
                'sequence'	=>	SORT_ASC
            ],
        ]);*/

        /*if(!empty($params["ordersSource"])){
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
        }elseif(empty($params['HistorySearch'])){
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
        $this->addCondition($query, 'responsibleUserID', true);*/

        return $onlyQuery ? $query : $dataProvider;
    }

    /**
     * @param ActiveQuery $query
     * @param string $attribute
     * @param bool $partialMatch
     */
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