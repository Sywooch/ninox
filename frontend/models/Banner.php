<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 24.11.15
 * Time: 14:46
 */

namespace frontend\models;


use yii\base\InvalidConfigException;

class Banner extends \common\models\Banner{

    public static function getByAlias($alias, $all = true){
        if(empty($alias)){
            throw new InvalidConfigException('Тип баннера не может быть пустым!');
        }
        $date = date('Y-m-d H:i:s');
            $q = self::find()->where(['banners_type.alias' => $alias, 'banners.state' => '1'])->
        andWhere(['or', 'banners.dateStart <= :date', 'banners.dateStart = \'0000-00-00 00:00:00\''], [
            ':date'  =>  $date
        ])->
        andWhere(['or', 'banners.dateEnd >= \':date\'', 'banners.dateEnd = \'0000-00-00 00:00:00\''], [
            ':date' => $date
        ])->
        andWhere('banners.banner_ru != \'\'')->
        leftJoin('banners_type', 'banners_type.id = banners.bannerTypeId')->
        orderBy('banners.bannerOrder');
        return $all ? $q->all() : $q->one();
    }

}