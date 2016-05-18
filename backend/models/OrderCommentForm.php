<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 17.05.16
 * Time: 12:53
 */

namespace backend\models;


use common\models\Comment;
use yii\base\Model;

class OrderCommentForm extends Model
{

    public $model;

    public $comment;

    public function rules()
    {
        return [
            [['comment'], 'string'],
            [['comment'], 'safe'],
            [['comment'], 'required'],
        ];
    }

    public function save(){
        $model = $this->model;

        $className = $model::className();

        if(sizeof(explode('\\', $className)) > 1){
            $t = explode('\\', $className);
            $t = array_reverse($t);
            $className = $t[0];
        }

        $comment = new Comment([
            'comment'   =>  $this->comment,
            'model'     =>  $className,
            'modelID'   =>  $model->getPrimaryKey(),
            'userID'    =>  \Yii::$app->user->identity->id
        ]);

        $comment->save();

        \Yii::trace($comment->getErrors());
    }

}