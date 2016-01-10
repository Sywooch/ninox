<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "domains_delivery_payments".
 *
 * @property integer $domainId
 * @property integer $deliveryType
 * @property integer $deliveryParam
 * @property integer $paymentType
 * @property integer $paymentParam
 * @property double $commissionPercent
 * @property double $commissionStatic
 * @property integer $enabled
 */
class DomainsDeliveryPayments extends \yii\db\ActiveRecord
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
            [['domainId', 'deliveryType', 'deliveryParam', 'paymentType', 'paymentParam'], 'required'],
            [['domainId', 'deliveryType', 'deliveryParam', 'paymentType', 'paymentParam', 'enabled'], 'integer'],
            [['commissionPercent', 'commissionStatic'], 'number'],
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
            'commissionPercent' => Yii::t('shop', 'Commission Percent'),
            'commissionStatic' => Yii::t('shop', 'Commission Static'),
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

	public function getConfig(){
		return DomainsDeliveryPayments::find()->
			joinWith('deliveryTypes')->
			joinWith('deliveryParams')->
			joinWith('paymentTypes')->
			joinWith('paymentParams')->
			where([DomainsDeliveryPayments::tableName().'.domainId' => \Yii::$app->params['domainInfo']['id'], DomainsDeliveryPayments::tableName().'.enabled' => 1, 'deliveryTypes.enabled' => 1])->
			andWhere(['OR', ['deliveryParams.enabled' => 1], [DomainsDeliveryPayments::tableName().'.deliveryParam' => 0]])->
			andWhere(['paymentTypes.enabled' => 1])->
			andWhere(['OR', ['paymentParams.enabled' => 1], [DomainsDeliveryPayments::tableName().'.paymentParam' => 0]])->
			all();
	}

	public function getConfigArray(){
		$configs = self::getConfig();
		$array = [];
		foreach($configs as $config){
			$array[$config->deliveryType]['name'] = $config->deliveryTypes[0]->description;
			$array[$config->deliveryType]['value'] = $config->deliveryTypes[0]->id;
			$array[$config->deliveryType][$config->deliveryParam]['name'] = $config->deliveryParams[0]->description;
			$array[$config->deliveryType][$config->deliveryParam]['value'] = $config->deliveryParams[0]->id;
		}
		return $array;
	}
}
