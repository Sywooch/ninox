<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "partners".
 *
 * @property string $ID
 * @property string $Code
 * @property string $Company
 * @property string $City
 * @property string $City2
 * @property string $Address
 * @property string $phone
 * @property string $Phone2
 * @property string $email
 * @property integer $priceGroup
 * @property double $discount
 * @property integer $type
 * @property integer $groupID
 * @property string $registrationTime
 * @property integer $deleted
 * @property string $cardNumber
 * @property string $Note2
 * @property string $shippingType
 * @property string $PaymentType
 * @property integer $blackList
 * @property string $blackListAddedTime
 * @property integer $money
 * @property string $birthday
 * @property string $password
 * @property string $lang
 * @property string $recipientID
 * @property string $auth_key
 * @property string $password_reset_token
 */
class Customer extends \yii\db\ActiveRecord
{
    public $name;
    public $surname;

    private $orders;

    public function behaviors()
    {
        return [
            'LoggableBehavior' => [
                'class' => 'sammaye\audittrail\LoggableBehavior',
            ]
        ];
    }

    public function getOrdersStats(){
        $b = [
            'count' =>  0,
            'summ'  =>  0
        ];
        if(empty($this->ID)){
            return $b;
        }

        $a = History::find()->select(['COUNT(`id`) as `count`, SUM(`actualAmount`) as `summ`'])->where(['customerID' => $this->ID, 'confirmed' => 1]);
        $a = $a->asArray()->all();

        return empty($a['0']) ? $b : $a['0'];
    }

    public function getOrders(){
        if(!empty($this->orders)){
            return $this->orders;
        }

        $this->orders = History::find()->where(['customerID' => $this->ID])->orderBy('added DESC')->all();

        return $this->orders;
    }

    public function getOrdersSummary(){
        $count = $summ = $all = 0;

        foreach($this->getOrders() as $order){
            if($order->deleted == 0){
                $count++;
                $summ += $order->actualAmount;
            }
            $all++;
        }

        return [
            'all'   =>  $all,
            'count' =>  $count,
            'summ'  =>  $summ
        ];
    }

    public function beforeSave($insert){
        if($this->isNewRecord){
            $this->ID = hexdec(uniqid());
            $this->Code = ($this->find()->count() + 1);;
            $this->registrationTime = date('Y-m-d H:i:s');
        }

        if((!empty($this->name) && !empty($this->surname)) && $this->name.' '.$this->surname != $this->Company){
            $this->Company = $this->name.' '.$this->surname;
        }

        return parent::beforeSave($insert);
    }

    public function afterFind(){
        $t = explode(' ', $this->Company);

        $this->name = isset($t[0]) ? $t[0] : '';
        $this->surname = isset($t[1]) ? $t[1] : '';

        return parent::afterFind();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partners';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shippingType', 'PaymentType', 'blackList'], 'safe'],
            [['ID', 'priceGroup', 'type', 'groupID', 'deleted', 'blackList', 'money'], 'integer'],
            [['discount'], 'number'],
            [['registrationTime', 'blackListAddedTime', 'birthday'], 'safe'],
            [['shippingType', 'PaymentType', 'password', 'lang'], 'string'],
            [['Code', 'Company', 'City', 'City2', 'Address', 'phone', 'Phone2', 'email', 'cardNumber', 'Note2', 'recipientID', 'auth_key', 'password_reset_token'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('common', 'ID'),
            'Code' => Yii::t('common', 'Code'),
            'Company' => Yii::t('common', 'Company'),
            'City' => Yii::t('common', 'City'),
            'City2' => Yii::t('common', 'City2'),
            'Address' => Yii::t('common', 'Address'),
            'phone' => Yii::t('common', 'Phone'),
            'Phone2' => Yii::t('common', 'Phone2'),
            'email' => Yii::t('common', 'Email'),
            'priceGroup' => Yii::t('common', 'Price Group'),
            'discount' => Yii::t('common', 'Discount'),
            'type' => Yii::t('common', 'Type'),
            'groupID' => Yii::t('common', 'Group ID'),
            'registrationTime' => Yii::t('common', 'Registration Time'),
            'deleted' => Yii::t('common', 'Deleted'),
            'cardNumber' => Yii::t('common', 'Card Number'),
            'Note2' => Yii::t('common', 'Note2'),
            'shippingType' => Yii::t('common', 'Тип доставки'),
            'PaymentType' => Yii::t('common', 'Тип оплаты'),
            'blackList' => Yii::t('common', 'Чёрный список'),
            'blackListAddedTime' => Yii::t('common', 'Black List Added Time'),
            'money' => Yii::t('common', 'Money'),
            'birthday' => Yii::t('common', 'Birthday'),
            'password' => Yii::t('common', 'Password'),
            'lang' => Yii::t('common', 'Lang'),
            'recipientID' => Yii::t('common', 'Recipient ID'),
            'auth_key' => Yii::t('common', 'Auth Key'),
            'password_reset_token' => Yii::t('common', 'Password Reset Token'),
        ];
    }
}
