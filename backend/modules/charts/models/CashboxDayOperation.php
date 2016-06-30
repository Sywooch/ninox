<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 30.06.16
 * Time: 16:37
 */

namespace backend\modules\charts\models;


use backend\models\History;
use backend\models\User;
use yii\base\Model;

class CashboxDayOperation extends Model
{

    const TYPE_SHOP_BUY = 0;
    const TYPE_CASHBOX_GET = 1;
    const TYPE_CASHBOX_PUT = 2;
    const TYPE_SELF_DELIVERY = 3;
    const TYPE_CASHBOX_SPEND = 4;

    public $date;

    public $orderID;

    public $type;

    public $sum;

    public $responsibleUser;

    public function rules(){
        return [
            [['date', 'type', 'sum', 'responsibleUser'], 'required'],
            [['orderID', 'type', 'responsibleUser'], 'integer'],
            [['date'], 'string'],
            [['sum'], 'number']
        ];
    }

    public function getOrder(){
        return History::findOne($this->orderID);
    }

    public function getResponsibleUserModel(){
        return User::findOne($this->responsibleUser);
    }

    public function getTypes(){
        return [
            self::TYPE_SHOP_BUY         =>  'Покупка',
            self::TYPE_CASHBOX_GET      =>  'Забранно',
            self::TYPE_CASHBOX_PUT      =>  'Добавлено',
            self::TYPE_SELF_DELIVERY    =>  'Самовывоз',
            self::TYPE_CASHBOX_SPEND    =>  'Траты'
        ];
    }

    public function attributeLabels()
    {
        return [
            'date'              =>  'Время',
            'orderID'           =>  '№ заказа',
            'type'              =>  'Тип',
            'sum'               =>  'Сумма',
            'responsibleUser'   =>  'Распорядился',
        ];
    }

}