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
use yii\web\BadRequestHttpException;

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

    public function rules(){
        return [
            [['city', 'region', 'deliveryInfo'], 'string'],
            [['deliveryType', 'deliveryParam'], 'number']
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

    public function getOrder(){
        return $this->order;
    }

    public function save(){
        if($this->order instanceof History == false){
            throw new BadRequestHttpException("Невозможно сохранить заказ, не передав в него заказ о_О");
        }

        $this->order->setAttributes([
            'deliveryCity'  =>  $this->city,
            'deliveryRegion'=>  $this->region,
            'deliveryType'  =>  $this->deliveryType,
            'deliveryParam' =>  $this->deliveryParam,
            'deliveryInfo'  =>  $this->deliveryInfo
        ]);

        return $this->order->save();
    }
    
}