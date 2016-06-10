<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 10.06.16
 * Time: 17:16
 */

namespace frontend\models;


use yii\base\Model;

class CartForm extends Model{
	public $phone = '';

	public function rules(){
		return [
			[['phone'], 'required'],
			[['phone'], 'string', 'max' => 20],
		];
	}

	public function attributeLabels()
	{
		return [
			'phone' =>  \Yii::t('shop', 'Ваш телефон:'),
		];
	}
}