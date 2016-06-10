<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 07.06.16
 * Time: 13:14
 */

namespace common\models;


use yii\base\Model;

class PriceRuleTermCategory extends Model{
	public $attribute = '';
	public $label = '';
	public $possibleOperands = [];
	public $default = [];

}