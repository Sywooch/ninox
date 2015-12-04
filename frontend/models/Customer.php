<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 15.10.15
 * Time: 15:01
 */

namespace frontend\models;


class Customer extends \common\models\Customer{

    public static function getList($options = []){
        $list = self::find();

        if(isset($options['columns'])){
            $list->select($options['columns']);
        }

        if(isset($options['asArray'])){
            $list->asArray();
        }

        return $list->all();
    }

}