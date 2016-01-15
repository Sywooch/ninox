<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 15.01.16
 * Time: 15:15
 */

namespace backend\models;


use common\models\Cashbox;
use yii\base\Model;

class CashboxForm extends Model{

    public $domain;
    public $autologin;
    public $name;
    public $defaultCustomer;
    public $defaultWholesaleCustomer;

    public function rules(){
        return [
            [['autologin', 'domain', 'name'], 'string'],
            [['defaultCustomer', 'defaultWholesaleCustomer'], 'integer'],
        ];
    }

    public function save(){
        $model = new Cashbox([
            'autologin'                 =>  $this->autologin,
            'domain'                    =>  $this->domain,
            'name'                      =>  $this->name,
            'defaultCustomer'           =>  $this->defaultCustomer,
            'defaultWholesaleCustomer'  =>  $this->defaultWholesaleCustomer,
        ]);

        if(!$model->validate()){
            return $model;
        }

        $model->save();

        return $model;
    }

    public function attributeLabels(){
        return [
            'domain'                        =>  'Домен',
            'autologin'                     =>  'Автологин',
            'name'                          =>  'Название',
            'defaultCustomer'               =>  'Стандартный покупатель',
            'defaultWholesaleCustomer'      =>  'Стандартный оптовый покупатель',
        ];
    }

}