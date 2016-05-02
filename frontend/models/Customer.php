<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 15.10.15
 * Time: 15:01
 */

namespace frontend\models;

/**
 * Class Customer
 * @package frontend\models
 * @property CustomerWishlist $wishes
 * @property int $wishesCount
 */
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

    /**
     * Возвращает желания клиента
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWishes(){
        return $this->hasMany(CustomerWishlist::className(), ['customerID' => 'ID']);
    }

    /**
     * Возвращает колличество желаний клиента
     *
     * @return int
     */
    public function getWishesCount(){
        return sizeof($this->wishes);
    }

}