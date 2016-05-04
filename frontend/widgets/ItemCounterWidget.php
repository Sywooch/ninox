<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 10/29/15
 * Time: 12:57 PM
 */

namespace frontend\widgets;


use frontend\models\Good;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;

class ItemCounterWidget extends Widget{

	public $value;
	public $store;
	public $model;

	public function init(){
		if(!empty($this->model)){
			$this->loadModel();
		}
	}

	public function loadModel(){
		if($this->model instanceof Good == false){
			throw new InvalidConfigException('Модель должна быть \frontend\Good');
		}
		if($this->model->isNewRecord){
			throw new InvalidConfigException('ID товара не может быть пустым');
		}
		$this->value    =  $this->model->inCart ? $this->model->inCart : 1;
		$this->store    =  $this->model->isUnlimited ? 1000 : $this->model->count;
	}

	public function renderMinus(){
		return Html::tag('div', '-', [
			'class'         =>  'minus'.($this->value <= 1 ? ' inhibit' : ''),
			'data-itemId'   =>  $this->model->ID,
		]);
	}

	public function renderPlus(){
		return Html::tag('div', '+', [
			'class'         =>  'plus'.($this->value >= $this->store ? ' inhibit' : ''),
			'data-itemId'   =>  $this->model->ID,
		]);
	}

	public function renderInput(){
		return Html::tag('input', '', [
			'value'         =>  $this->value,
			'name'          =>  'count',
			'class'         =>  'count',
			'type'          =>  'text',
			'data-itemId'   =>  $this->model->ID,
			'data-store'    =>  $this->store,
			'data-inCart'   =>  $this->model->inCart,
			'data-value'    =>  $this->value,
			'autocomplete'  =>  'off'
		]);
	}

	public function run(){
		return $this->model->enabled && ($this->model->count > 0 || $this->model->isUnlimited) ?
			Html::tag('div',
				$this->renderMinus().$this->renderInput().$this->renderPlus(),
				[
					'class' => 'item-counter'
				]
			) : '';
	}
} 