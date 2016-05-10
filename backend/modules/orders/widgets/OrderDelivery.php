<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 09.05.16
 * Time: 13:34
 */

namespace backend\modules\orders\widgets;


use common\models\DeliveryType;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;

class OrderDelivery extends Widget
{

    public $deliveryTypeAttribute = 'deliveryType';
    public $deliveryTypeID = 'deliveryTypeField';

    public $deliveryParamAttribute = 'deliveryParam';
    public $deliveryParamID = 'deliveryParamField';

    public $deliveryInfoAttribute = 'deliveryInfo';

    public $modelName = 'OrderPreviewForm';

    public function run(){
        return
            Select2::widget([
                'name'          =>  $this->modelName.'['.$this->deliveryTypeAttribute.']',
                'pluginOptions' =>  [
                    'ajax'  =>  [
                        'url'       =>  Url::to(['/orders/get-deliveries']),
                        'dataType'  =>  'json',
                        'data'      =>  new JsExpression('function(params){ return {type: "deliveryType"} }')
                    ]
                ]
            ]).
            DepDrop::widget([
                'data'          =>  [],
                'id'            =>  $this->deliveryParamID,
                'name'          =>  $this->modelName.'['.$this->deliveryParamAttribute.']',
                'pluginOptions' =>  [
                    'depends'   =>  [$this->deliveryTypeID],
                    'url'       =>  Url::to(['/orders/get-deliveries'])
                ]
            ]);
    }
    
}