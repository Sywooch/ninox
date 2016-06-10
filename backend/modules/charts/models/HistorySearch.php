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
            $query->andWhere("`added` > '{$this->dateFrom}'");
        }

        if(!empty($this->dateTo)){
            $query->andWhere("`added` < '{$this->dateTo}'");
        }

        return $query;
    }
    
    
    
    
}