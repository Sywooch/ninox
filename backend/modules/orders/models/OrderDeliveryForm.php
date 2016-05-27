<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 27.05.16
 * Time: 17:10
 */

namespace backend\modules\orders\models;


use common\models\History;
use yii\base\Model;

class OrderDeliveryForm extends Model
{

    public $city;
    
    public $region;
    
    public $deliveryType;
    
    public $deliveryParam;
    
    public $deliveryInfo;

    /**
     * @var History
     */
    private $order;

    public function attributeLabels()
    {
        return [
            'city'          =>  'Город',
            'region'        =>  'Область',
            'deliveryType'  =>  'Тип доставки',
            'deliveryParam' =>  'Компания',
            'deliveryInfo'  =>  'Инфо',
        ];
    }

    /**
     * @param History $order
     */
    public function loadOrder($order){
        $this->order = $order;

        return $this->setAttributes([
            'city'          =>  $order->deliveryCity,
            'region'        =>  $order->deliveryRegion,
            'deliveryType'  =>  $order->deliveryType,
            'deliveryParam' =>  $order->deliveryParam,
            'deliveryInfo'  =>  $order->deliveryInfo
        ]);
    }

    public function save(){

    }
    
}