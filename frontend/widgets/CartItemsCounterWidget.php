<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 10/29/15
 * Time: 12:57 PM
 */

namespace app\widgets;


use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;

class CartItemsCounterWidget extends Widget{

	public $itemID;
	public $value;
	public $store;
	public $inCart;

	public function init(){}

	public function setOptions($options){
		$this->itemID = $options['itemID'];
		$this->value = $options['value'];
		$this->store = $options['store'];
		$this->inCart = $options['inCart'];
	}

	public function renderMinus(){
		if(empty($this->itemID)){
			throw new InvalidConfigException('ID товара не может быть пустым');
		}
		return Html::tag('div', '', [
			'class'         =>  'minus',
			'data-itemId'   =>  $this->itemID,
			'data-count'    =>  -1,
		]);
	}

	public function renderPlus(){
		if(empty($this->itemID)){
			throw new InvalidConfigException('ID товара не может быть пустым');
		}
		return Html::tag('div', '', [
			'class'         =>  'plus',
			'data-itemId'   =>  $this->itemID,
			'data-count'    =>  1,
		]);
	}

	public function renderDelete(){
		if(empty($this->itemID)){
			throw new InvalidConfigException('ID товара не может быть пустым');
		}
		return Html::button(\Yii::t('shop', 'Удалить'), [
			'class'         =>  'remove-item',
			'data-itemId'   =>  $this->itemID,
			'data-count'    =>  0,
		]);
	}

	public function renderInput(){
		if(empty($this->itemID)){
			throw new InvalidConfigException('ID товара не может быть пустым');
		}
		return Html::tag('input', '', [
			'value'         =>  $this->value,
			'readonly'      =>  'readonly',
			'name'          =>  'count',
			'class'         =>  'count',
			'type'          =>  'text',
			'data-itemId'   =>  $this->itemID,
			'data-store'    =>  $this->store,
			'data-inCart'   =>  $this->inCart,

		]);
	}

	public function run(){
		return Html::tag('div', $this->renderMinus().$this->renderInput().$this->renderPlus(),
			[
				'class' => 'counter'
			]);
	}

} 