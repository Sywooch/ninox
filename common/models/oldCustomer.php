<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 08.04.16
 * Time: 17:12
 */

namespace common\models;
	/**
	 * This is the model class for table "partners".
	 *
	- * @property integer $ID
	 * @property string $Code
	 * @property string $Company
	- * @property string $Company2
	- * @property string $MOL
	- * @property string $MOL2
	 * @property string $City
	 * @property string $City2
	- * @property integer $VerifiedCity
	 * @property string $Address
	- * @property string $Address2
	- * @property string $Phone
	 * @property string $Phone2
	- * @property string $Fax
	- * @property string $eMail
	- * @property string $TaxNo
	- * @property string $Bulstat
	- * @property string $BankName
	- * @property string $BankCode
	- * @property string $BankAcct
	- * @property string $BankVATName
	- * @property string $BankVATCode
	- * @property string $BankVATAcct
	- * @property integer $PriceGroup
	- * @property double $Discount
	- * @property integer $Type
	- * @property integer $IsVeryUsed
	- * @property integer $UserID
	- * @property integer $GroupID
	- * @property string $UserRealTime
	- * @property integer $Deleted
	- * @property string $CardNumber
	- * @property string $Note1
	 * @property string $Note2
	- * @property integer $PaymentDays
	- * @property string $ShippingType
	 * @property string $PaymentType
	- * @property integer $black
	- * @property string $blackDate
	- * @property string $utma
	 * @property integer $money
	 * @property string $birthday
	- * @property string $cityID
	 * @property string $password
	 * @property string $lang
	 */

class oldCustomer extends \yii\db\ActiveRecord
{
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
			            [['VerifiedCity', 'PriceGroup', 'Type', 'IsVeryUsed', 'UserID', 'GroupID', 'Deleted', 'PaymentDays', 'black', 'money'], 'integer'],
			            [['Discount'], 'number'],
			            [['UserRealTime', 'blackDate', 'birthday'], 'safe'],
			            [['ShippingType', 'PaymentType', 'black'], 'required'],
			            [['ShippingType', 'PaymentType', 'utma', 'cityID', 'password', 'lang'], 'string'],
			            [['Code', 'Company', 'Company2', 'MOL', 'MOL2', 'City', 'City2', 'Address', 'Address2', 'Phone', 'Phone2', 'Fax', 'eMail', 'TaxNo', 'Bulstat', 'BankName', 'BankCode', 'BankAcct', 'BankVATName', 'BankVATCode', 'BankVATAcct', 'CardNumber', 'Note1', 'Note2'], 'string', 'max' => 255]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			            'ID' => 'ID',
			            'Code' => 'Код',
			            'Company' => 'ФИО',
			            'Company2' => 'ФИО',
			            'MOL' => 'Mol',
			            'MOL2' => 'Mol2',
			            'City' => 'Город',
			            'City2' => 'Город2',
			            'VerifiedCity' => 'Город подтверждён',
			            'Address' => 'Адрес',
			            'Address2' => 'Адрес2',
			            'Phone' => 'Телефон',
			            'Phone2' => 'Телефон2',
			            'Fax' => 'Факс',
			            'eMail' => 'email',
			            'TaxNo' => 'Tax No',
			            'Bulstat' => 'Bulstat',
			            'BankName' => 'Bank Name',
			            'BankCode' => 'Bank Code',
			            'BankAcct' => 'Bank Acct',
			            'BankVATName' => 'Bank Vatname',
			            'BankVATCode' => 'Bank Vatcode',
			            'BankVATAcct' => 'Bank Vatacct',
			            'PriceGroup' => 'Price Group',
			            'Discount' => 'Скидка',
			            'Type' => 'Type',
			            'IsVeryUsed' => 'Is Very Used',
			            'UserID' => 'User ID',
			            'GroupID' => 'Group ID',
			            'UserRealTime' => 'User Real Time',
			            'Deleted' => 'Удалён',
			            'CardNumber' => 'Номер карты',
			            'Note1' => 'Note1',
			            'Note2' => 'Note2',
			            'PaymentDays' => 'Payment Days',
			            'ShippingType' => 'Тип доставки',
			            'PaymentType' => 'Тип оплаты',
			            'black' => 'Чёрный список',
			            'blackDate' => 'Black Date',
			            'utma' => 'Utma',
			            'money' => 'Money',
			            'birthday' => 'Birthday',
			            'cityID' => 'City ID',
			            'password' => 'Password',
			            'lang' => 'Lang',
		];
	}
}