<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 15.10.15
 * Time: 15:01
 */

namespace frontend\models;


class Customer extends \common\models\Customer{

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['name', 'surname', 'city', 'region', 'deliveryType', 'deliveryInfo', 'deliveryParam', 'paymentType', 'paymentParam', 'paymentInfo'], 'safe']
        ]);
    }

    public function setName($value){
        $array = explode(' ', $this->Company);

        $array[0] = $value;

        $this->Company = implode(' ', $array);
    }

    public function setSurname($value){
        $array = explode(' ', $this->Company);

        $array[1] = $value;

        $this->Company = implode(' ', $array);
    }

    public function setCity($value){
        $array = explode(', ', $this->City);

        $array[0] = $value;

        $this->City = implode(', ', $array);
    }

    public function setRegion($value){
        $array = explode(', ', $this->City);

        $array[1] = $value;

        $this->City = implode(', ', $array);
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

}