<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 18.04.16
 * Time: 16:11
 */

namespace frontend\widgets;


use frontend\models\Good;
use yii\base\InvalidConfigException;
use yii\bootstrap\Widget;
use yii\helpers\Html;

class ItemBuyButtonWidget extends Widget{

	public $value;
	public $model;
	public $btnClass = 'small-button';
	public $options;

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

		switch($this->btnClass){
			case 'small-button':
				$this->value = $this->model->canBuy ?
					($this->model->inCart ?
						\Yii::t('shop', 'В корзине!') : \Yii::t('shop', 'Купить!')
					) : \Yii::t('shop', "Нет\r\nв наличии");
				break;
			case 'mini-button':
			case 'micro-button':
				$this->value = '';
			$this->btnClass .= ' icon-cart';
				break;
			default:
				$this->value = $this->model->canBuy ?
					($this->model->inCart ?
						\Yii::t('shop', 'В корзине!') : \Yii::t('shop', 'Купить!')
					) : \Yii::t('shop', "Нет в наличии");
				break;
		}

		$this->options = [
			'class'         =>  'button '.($this->model->canBuy ?
					($this->model->inCart ?
						'green-button open-cart ' : 'yellow-button buy '
					) : 'gray-button out-of-stock ').$this->btnClass,
			'data-itemId'   =>  $this->model->ID,
			'data-count'    =>  '1',
		];
	}

	public function run(){
		return Html::button($this->value, $this->options);
	}

}