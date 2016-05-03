<?php
/**
 * Created by PhpStorm.
 * User: alone
 * Date: 22.04.16
 * Time: 15:18
 */

namespace frontend\models;


use common\models\GoodsComment;
use Yii;
use yii\base\Model;

class CommentForm extends Model
{
	public $name;
	public $email;
	public $comment;
	public $itemID;
	public $parent;
	public $type;

	public function init(){
		parent::init();
		if(!\Yii::$app->user->isGuest){
			$this->name = \Yii::$app->user->identity->Name;
			$this->email = \Yii::$app->user->identity->email;
		}
	}

	public function save(){
		$model = new GoodsComment();
		$model->setAttributes([
			'goodID'        =>  $this->itemID,
			'target'        =>  $this->parent,
			'type'          =>  $this->type,
			'who'           =>  $this->name,
			'email'         =>  $this->email,
			'what'          =>  $this->comment,
		], false);

		if($this->validate() && $model->save(false)){
			return true;
		}
		return false;
	}

	public function rules(){
		return [
			[['itemID', 'name', 'comment'], 'required'],
			[['itemID', 'parent', 'type'], 'integer'],
			[['name', 'email', 'comment'], 'string'],
		];
	}

	public function attributeLabels(){
		return [
			'name' => Yii::t('shop', 'Имя'),
			'email' => Yii::t('shop', 'Эл. почта'),
			'comment' => Yii::t('shop', 'Ваше сообщение'),
		];
	}

}