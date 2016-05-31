<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 22.05.16
 * Time: 18:04
 */

namespace backend\modules\orders\models;


use backend\models\History;
use yii\base\Model;
use yii\web\NotFoundHttpException;

class CustomerCommentForm extends Model
{

    public $orderID;

    public $customerComment;

    public function rules(){
        return [
            [['orderID'], 'integer'],
            [['customerComment'], 'trim'],
            [['customerComment'], 'string'],
        ];
    }

    /**
     * @param History $order
     */
    public function loadOrder($order){
        $this->setAttributes([
            'orderID'           =>  $order->id,
            'customerComment'   =>  $order->customerComment
        ]);
    }

    public function save(){
        $order = History::findOne($this->orderID);

        if(!$order){
            throw new NotFoundHttpException("Заказ с идентификатором {$this->orderID} не найден!");
        }

        $order->setAttributes([
            'customerComment'   =>  $this->customerComment
        ]);

        return $order->save(false);
    }

}