<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 07.06.16
 * Time: 13:14
 */

namespace backend\models;


use yii\base\Model;

class PriceRuleTerm extends Model{
	public $attribute = '';
	public $label = '';
	public $possibleOperands = [];
	public $default = [];

}