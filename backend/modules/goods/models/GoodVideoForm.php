<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 03.08.16
 * Time: 11:36
 */

namespace backend\modules\goods\models;


use common\models\Good;
use common\models\GoodsVideo;
use yii\base\Model;

class GoodVideoForm extends Model{

	/**
	 * @type integer ID товара
	 */
	public $id;

	/**
	 * @type string ссылка на видео на ютубе
	 */
	public $url;

	/**
	 * Возвращает названия аттрибутов
	 *
	 * @return array
	 */
	public function attributeLabels()
	{
		return [
			'url'   =>  'Ссылка на видео',
		];
	}

	public function rules(){
		return [
			[['id'], 'integer'],
			[['url'], 'trim'],
			[['url'], 'string', 'max' => 255],
			[['url'], 'required'],
			[['url'], 'validateUrl'],
		];
	}

	public function validateUrl($attribute, $params){
		$components = parse_url($this->$attribute);
		if(empty($components['query'])){
			$this->addError($attribute, 'Не похожа на ссылку с сайта youtube! Проверьте правильность ссылки!');
		}else{
			parse_str($components['query']);
			if(empty($v)){
				$this->addError($attribute, 'Не удается найти идентификатор видео! Проверьте правильность ссылки!');
			}
			if(!empty($this->id)){
				$video = GoodsVideo::findOne(['goodID' => $this->id, 'video' => $v]);
				if(!empty($video)){
					$this->addError($attribute, 'У товара уже есть такое видео!');
				}
			}
		}
	}

	public function save(){
		if(!empty($this->id)){
			$good = Good::findOne($this->id);
			if(!empty($good) && $this->validate()){
				$v = '';
				$components = parse_url($this->url);
				parse_str($components['query']);
				$video = new GoodsVideo([
					'goodID'    =>  $good->ID,
					'video'     =>  $v,
				]);
				if($video->save()){
					return $v;
				}
			}
		}

		return false;
	}

}