<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 09.06.16
 * Time: 22:11
 */

namespace backend\models;

class Siteuser extends \common\models\Siteuser
{

    public static function getActive(){
        return Siteuser::find()
            ->joinWith('accessDomains')
            ->andWhere(['`siteusers`.`active`' => '1', '`siteusers`.`showInStat`' => '1', '`subDomainsAccess`.`subDomainId`' => \Yii::$app->params['configuration']->id])
            ->all();
    }

    public function getOrders(){
        return $this->hasMany(History::className(), ['responsibleUserID' => 'id']);
    }

    public function getDoneOrders(){
        return $this->getOrders()->andWhere(['done' => 1]);
    }

    public function getOrdersCount(){
        return $this->getDoneOrders()->count();
    }

    public function getCompletedOrdersCount($dateFrom = null, $dateTo = null){
        $query = $this->getDoneOrders();

        if(!empty($dateFrom)){
            $query->andWhere("`added` > '{$dateFrom}'");
        }

        if(!empty($dateTo)){
            $query->andWhere("`added` < '{$dateTo}'");
        }

        return $query->count();
    }

    //public function get

}