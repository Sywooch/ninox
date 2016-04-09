<?php

namespace common\models;

use frontend\models\CustomerReceiver;
use Yii;

/**
 * This is the model class for table "partners".
 *
 * @property integer $ID
 * @property integer $Code
 * @property string $Company
 * @property string $name
 * @property string $surname
 * @property string $city
 * @property string $region
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
 * @property integer $deliveryType
 * @property integer $paymentType
 * @property integer $blackList
 * @property string $blackListAddedTime
 * @property integer $money
 * @property string $birthday
 * @property string $password
 * @property string $lang
 * @property string $recipientID
 * @property string $auth_key
 * @property string $password_reset_token
 * @property string $giveFeedbackClosed
 * @property integer $deliveryParam
 * @property string $deliveryInfo
 * @property integer $paymentParam
 * @property string $paymentInfo
 */
class Customer extends \yii\db\ActiveRecord
{
    /**
     * @type History[]
     */
    private $_orders = [];

    /**
     * @type CustomerContacts[]
     */
    private $_phones = [];

    /**
     * @type CustomerContacts[]
     */
    private $_emails = [];

    /**
     * @type CustomerAddresses
     */
    private $_recipient = null;

    /**
     * Возвращает имя клиента
     *
     * @return string
     */
    public function getName(){
        $array = explode(' ', $this->Company);

        return isset($array[0]) ? $array[0] : '';
    }

    /**
     * Возвращает фамилию клиента
     *
     * @return string
     */
    public function getSurname(){
        $array = explode(' ', $this->Company);

        return isset($array[1]) ? $array[1] : '';
    }

    /**
     * Возвращает город клиента
     *
     * @return string
     */
    public function getCity(){
        $array = explode(', ', $this->City);

        return isset($array[0]) ? $array[0] : '';
    }

    /**
     * Возвращает регион клиента
     *
     * @return string
     */
    public function getRegion(){
        $array = explode(', ', $this->City);

        return isset($array[1]) ? $array[1] : '';
    }

    /**
     * Сохраняет имя клиента
     *
     * @param $value string
     */
    public function setName($value){
        $array = explode(' ', $this->Company);

        $array[0] = $value;

        $this->Company = implode(' ', $array);
    }

    /**
     * @param $value string
     */
    public function setSurname($value){
        $array = explode(' ', $this->Company);

        $array[1] = $value;

        $this->Company = implode(' ', $array);
    }

    /**
     * @param $value string
     */
    public function setCity($value){
        $array = explode(', ', $this->City);

        $array[0] = $value;

        $this->City = implode(', ', $array);
    }

    /**
     * @param $value string
     */
    public function setRegion($value){
        $array = explode(', ', $this->City);

        $array[1] = $value;

        $this->City = implode(', ', $array);
    }

    /**
     * @return \common\models\CustomerContacts[]
     */
    public function getPhones(){
        if(empty($this->_phones)){
            $this->_phones = CustomerContacts::findAll(['partnerID' => $this->ID, 'type' => CustomerContacts::TYPE_PHONE]);
        }

        return $this->_phones;
    }

    /**
     * @return CustomerContacts|bool
     */
    public function getPrimaryPhone(){
        foreach($this->phones as $phone){
            if($phone->primary == 1){
                return $phone;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getPhone(){
        return $this->primaryPhone ? $this->primaryPhone->value : '';
    }

    /**
     * @return \common\models\CustomerContacts[]
     */
    public function getEmails(){
        if(empty($this->_emails)){
            $this->_emails = CustomerContacts::findAll(['partnerID' => $this->ID, 'type' => CustomerContacts::TYPE_EMAIL]);
        }

        return $this->_emails;
    }

    /**
     * @return CustomerContacts|bool
     */
    public function getPrimaryEmail(){
        foreach($this->emails as $email){
            if($email->primary == 1){
                return $email;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getEmail(){
        return $this->primaryEmail ? $this->primaryEmail->value : '';

    }

    /**
     * @return History[]
     */
    public function getOrders(){
        if(empty($this->_orders)){
            $this->_orders = History::find()->where(['customerID' => $this->ID])->orderBy('added DESC')->all();
        }

        return $this->_orders;
    }

    /**
     * @todo пересмотреть использование этого метода, и актуальность его расположения здесь
     *
     * @return array|\yii\db\ActiveRecord
     */
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

    /**
     * @todo пересмотреть использование этого метода, и актуальность его расположения здесь
     *
     * @return array
     */
    public function getOrdersSummary(){
        $count = $sum = $all = 0;

        foreach($this->orders as $order){
            if($order->deleted == 0){
                $count++;
                $sum += $order->actualAmount;
            }
            $all++;
        }

        return [
            'all'   =>  $all,
            'count' =>  $count,
            'summ'  =>  $sum
        ];
    }

    public function getRecipient($recipientID = null){
        $query = CustomerAddresses::find()->where(['partnerID' => $this->ID]);

        if($recipientID != null){
            return $query->andWhere(['ID' => $recipientID])->one();
        }

        if(!empty($this->_recipient)){
            return $this->_recipient;
        }

        return $this->_recipient = $query->andWhere(['default' => 1])->one();
    }

    public function setRecipient($customerRecipient){
        $this->_recipient = $customerRecipient;
    }

    public function beforeSave($insert){
        if($this->isNewRecord){
            $this->ID = hexdec(uniqid());
            $this->Code = ($this->find()->count() + 1);;
            $this->registrationTime = date('Y-m-d H:i:s');
        }

        if($this->recipient){
            $this->recipient->save(false); //TODO: в миграции m160131_110736_default_cashboxes вывалилась ошибка при
            // создании дефолтных оптового и розничного покупателей, что метод save вызывается не на объекте
        }

        return parent::beforeSave($insert);
    }

    public function afterFind(){
        if(empty($this->recipient)){
            $this->recipient = new CustomerAddresses([
                'partnerID'     =>  $this->ID,
                'region'        =>  $this->region,
                'city'          =>  $this->city,
                'deliveryType'  =>  $this->deliveryType,
                'deliveryParam' =>  $this->deliveryParam,
                'deliveryInfo'  =>  $this->deliveryInfo,
                'paymentType'   =>  $this->paymentType,
                'paymentParam'  =>  $this->paymentParam,
                'paymentInfo'   =>  $this->paymentInfo,
                'default'       =>  1
            ]);
        }

        $this->recipient->save(false);
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
            [['ID', 'blackList'], 'required'],
            [['ID', 'Code', 'priceGroup', 'type', 'groupID', 'deleted', 'deliveryType', 'paymentType', 'blackList', 'money', 'deliveryParam', 'paymentParam'], 'integer'],
            [['discount'], 'number'],
            [['registrationTime', 'blackListAddedTime', 'birthday', 'giveFeedbackClosed'], 'safe'],
            [['password', 'lang'], 'string'],
            [['Company', 'City', 'City2', 'Address', 'phone', 'Phone2', 'email', 'cardNumber', 'Note2', 'recipientID', 'auth_key', 'password_reset_token', 'deliveryInfo', 'paymentInfo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID'        => 'ID',
            'Code'      => 'Code',
            'Company'   => 'Company',
            'City'      => 'City',
            'City2'     => 'City2',
            'Address'   => 'Address',
            'phone'     => 'Phone',
            'Phone2'    => 'Phone2',
            'email'     => 'Email',
            'priceGroup'=> 'Price Group',
            'discount'  => 'Discount',
            'type'      => 'Type',
            'groupID'   => 'Group ID',
            'deleted'   => 'Deleted',
            'cardNumber'=> 'Card Number',
            'Note2'     => 'Note2',
            'blackList' => 'Чёрный список',
            'money'     => 'Money',
            'birthday'  => 'Birthday',
            'password'  => 'Password',
            'lang'      => 'Lang',
            'auth_key'  => 'Auth Key',
            'deliveryType'  => 'Delivery Type',
            'deliveryParam' => 'Delivery Param',
            'deliveryInfo'  => 'Delivery Info',
            'paymentType'   => 'Payment Type',
            'paymentParam'  => 'Payment Param',
            'paymentInfo'   => 'Payment Info',
            'recipientID'   => 'Recipient ID',
            'registrationTime' => 'Registration Time',
            'blackListAddedTime' => 'Black List Added Time',
            'password_reset_token' => 'Password Reset Token',
            'giveFeedbackClosed' => 'Give Feedback Closed',
        ];
    }
}
