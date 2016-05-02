<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 15.10.15
 * Time: 15:01
 */

namespace frontend\models;


class Customer extends \common\models\Customer{

    public static function find(){
        return parent::find()->with('wishes');
    }

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

    public function getWishes(){
        return $this->hasMany(CustomerWishlist::className(), ['customerID' => 'ID']);
    }

    public function getWishesCount(){
        return count($this->wishes);
    }

}