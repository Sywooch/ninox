<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 29.04.16
 * Time: 17:34
 */

namespace frontend\modules\account\models;


use yii\base\Model;

class CustomerInfoForm extends Model
{

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $surname;

    /**
     * @var string
     */
    public $phone;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $deliveryType;

    /**
     * @var string
     */
    public $deliveryParam;

    /**
     * @var string
     */
    public $deliveryInfo;

    public function attributeLabels(){
        return [
            'name'          =>  \Yii::t('shop', 'Имя'),
            'surname'       =>  \Yii::t('shop', 'Фамилия'),
            'phone'         =>  \Yii::t('shop', 'Телефон'),
            'email'         =>  \Yii::t('shop', 'Эл. Почта'),
            'deliveryType'  =>  \Yii::t('shop', 'Тип доставки'),
            'deliveryParam' =>  \Yii::t('shop', 'Параметр доставки'),
            'deliveryInfo'  =>  \Yii::t('shop', 'Инфо доставки'),
        ];
    }

    public function save(){

    }

}