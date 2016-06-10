<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 10/09/15
 * Time: 12:13
 */

namespace common\components;

use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;

class SocialButtonWidget extends Widget{

	public $link            =   '#';
	public $linkTag         =   'span';
	public $type            =   null;
	public $items           =   [];

	private $classess       =   [
		'facebook'          =>  'shop-facebook',
		'vkontakte'         =>  'shop-vkontakte',
		'googleplus'        =>  'shop-googleplus',
		'odnoklassniki'     =>  'shop-odnoklassniki-rect',
		'youtube'           =>  'shop-youtube',
		'instagram'         =>  'shop-instagram',
		'twitter'           =>  'shop-twitter-squared'
	];


	public function init(){
		if(empty($this->items)){
			if(empty($this->type)){
				throw new InvalidConfigException('Тип ссылки не может быть пустым!');
			}
			if(empty($this->classess[$this->type])){
				throw new InvalidConfigException('Неверно указан тип ссылки!');
			}
		}else{
			foreach($this->items as $item){
				if(empty($item['type'])){
					throw new InvalidConfigException('Тип ссылки не может быть пустым!');
				}
				if(empty($this->classess[$item['type']])){
					throw new InvalidConfigException('Неверно указан тип ссылки!');
				}
			}
		}
	}

	public function renderLink($link, $class, $tag = 'span'){
		$linksOptions = [
			'class' => 'link-hide',
			'data-target' => '_blank',
			'target' => '_blank'
		];

		return Html::tag($tag, Html::tag('i', '', ['class' => $class]), array_merge([
			'href' => $link,
			'data-href' => $link
		], $linksOptions));
	}

	public function run(){
		if(empty($this->items)){
			return $this->renderLink($this->link, $this->classess[$this->type], $this->linkTag);
		}else{
			foreach($this->items as $item){
				echo $this->renderLink($item['link'], $this->classess[$item['type']], $item['linkTag']);
			}
		}
	}
}