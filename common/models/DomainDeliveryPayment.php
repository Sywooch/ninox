<?php

namespace common\models;

use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "domains_delivery_payments".
 *
 * @property integer $domainId
 * @property integer $deliveryType
 * @property integer $deliveryParam
 * @property integer $paymentType
 * @property integer $paymentParam
 * @property string $options
 * @property integer $enabled
 */
class DomainDeliveryPayment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'domains_delivery_payments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['domainId', 'deliveryType', 'deliveryParam', 'paymentType', 'paymentParam', 'options'], 'required'],
            [['domainId', 'deliveryType', 'deliveryParam', 'paymentType', 'paymentParam', 'enabled'], 'integer'],
            [['options'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'domainId' => Yii::t('shop', 'Domain ID'),
            'deliveryType' => Yii::t('shop', 'Delivery Type'),
            'deliveryParam' => Yii::t('shop', 'Delivery Param'),
            'paymentType' => Yii::t('shop', 'Payment Type'),
            'paymentParam' => Yii::t('shop', 'Payment Param'),
            'options' => Yii::t('shop', 'Options'),
            'enabled' => Yii::t('shop', 'Enabled'),
        ];
    }

	public function getDeliveryTypes(){
		return $this->hasMany(DeliveryType::className(), ['id' => 'deliveryType']);
	}

	public function getDeliveryParams(){
		return $this->hasMany(DeliveryParam::className(), ['id' => 'deliveryParam']);
	}

	public function getPaymentTypes(){
		return $this->hasMany(PaymentType::className(), ['id' => 'paymentType']);
	}

	public function getPaymentParams(){
		return $this->hasMany(PaymentParam::className(), ['id' => 'paymentParam']);
	}

	public static function getConfig(){
		return self::find()->
			joinWith('deliveryTypes')->
			joinWith('deliveryParams')->
			joinWith('paymentTypes')->
			joinWith('paymentParams')->
			where([self::tableName().'.domainId' => \Yii::$app->params['domainInfo']['id'], self::tableName().'.enabled' => 1, 'deliveryTypes.enabled' => 1])->
			andWhere(['OR', ['deliveryParams.enabled' => 1], [self::tableName().'.deliveryParam' => 0]])->
			andWhere(['paymentTypes.enabled' => 1])->
			andWhere(['OR', ['paymentParams.enabled' => 1], [self::tableName().'.paymentParam' => 0]])->
			all();
	}

	public static function getConfigArray(){
		$configs = self::getConfig();
		$array = [];
		if(!empty($configs)){
			foreach($configs as $config){
				$array['deliveryTypes'][$config->deliveryType]['name'] = $config->deliveryTypes[0]->description;
				$array['deliveryTypes'][$config->deliveryType]['value'] = $config->deliveryTypes[0]->id;
				$array['deliveryTypes'][$config->deliveryType]['modifyLabel'] = $config->deliveryTypes[0]->modifyLabel;
				$array['deliveryTypes'][$config->deliveryType]['params'][$config->deliveryParam]['name'] = $config->deliveryParams[0]->description;
				$array['deliveryTypes'][$config->deliveryType]['params'][$config->deliveryParam]['options'] = (object)array_merge((array)Json::decode($config->options, false), (array)Json::decode($config->deliveryParams[0]->options, false));

				$array['paymentTypes'][$config->paymentType]['name'] = $config->paymentTypes[0]->description;
				$array['paymentTypes'][$config->paymentType]['value'] = $config->paymentTypes[0]->id;
				$array['paymentTypes'][$config->paymentType]['modifyLabel'] = $config->paymentTypes[0]->modifyLabel;
				$array['paymentTypes'][$config->paymentType]['params'][$config->paymentParam]['name'] = !empty($config->paymentParams[0]) ? $config->paymentParams[0]->description : 'Default Param';
			}

			foreach($array as $type => $value){
				foreach($value as $k => $v){
					if(sizeof($v['params']) < 2){
						switch($v['modifyLabel']){
							case 1:
								$array[$type][$k]['name'] = reset($v['params'])['name'];
								break;
							case 2:
								$array[$type][$k]['name'] .= ' '.reset($v['params'])['name'];
								break;
							case 0:
							default:
								break;
						}
					}
				}
			}

		}
		return $array;
	}
}
