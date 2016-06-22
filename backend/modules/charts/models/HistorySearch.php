<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 09.06.16
 * Time: 17:30
 */

namespace backend\modules\charts\models;


use backend\models\HistoryWithoutRelations;

class HistorySearch extends HistoryWithoutRelations
{

    public $dateFrom = '';
    public $dateTo = '';

    public function search($params){
        $query = self::find();

        if(empty($params['withDeleted']) || $params['withDeleted'] != 'true'){
            $query->andWhere(['Deleted' => 0]);
        }

        if(!empty($this->dateFrom)){
            if(!filter_var($this->dateFrom, FILTER_VALIDATE_INT)){
                $this->dateFrom = strtotime($this->dateFrom);
            }

            $query->andWhere("`added` >= '{$this->dateFrom}'");
        }

        if(!empty($this->dateTo)){
            if(!filter_var($this->dateTo, FILTER_VALIDATE_INT)){
                $this->dateTo = strtotime($this->dateTo);
            }

            $query->andWhere("`added` < '{$this->dateTo}'");
        }

        if(array_key_exists('smartFilter', $params)){
            switch($params['smartFilter']){
                case 'shop':
                    $query->andWhere(['sourceType' => self::SOURCETYPE_SHOP]);
                    break;
                case 'hmelnytsky':
                    $query->andWhere(['sourceType' => self::SOURCETYPE_SHOP, 'orderSource' => 2]);
                    break;
                case 'internet':
                    $query->andWhere(['sourceType' => self::SOURCETYPE_INTERNET])->andWhere("`responsibleUserID` != '59'");
                    break;
                case 'childsGoods':
                    $query->andWhere(['sourceType' => self::SOURCETYPE_INTERNET, 'responsibleUserID' => '59']);
                    break;
                case 'all':
                default:
                    $query->andWhere("`orderSource` != '0'");
                    break;
            }
        }

        return $query;
    }
    
    
    
    
}