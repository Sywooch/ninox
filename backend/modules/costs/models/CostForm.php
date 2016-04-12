<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 10.04.16
 * Time: 18:32
 */

namespace backend\modules\costs\models;


use common\models\Cost;
use yii\base\Model;

class CostForm extends Model
{

    /**
     * @type double
     */
    public $sum;

    /**
     * @type integer
     */
    public $type;

    /**
     * @type string
     */
    public $date;

    /**
     * @type string
     */
    public $comment;

    public function rules()
    {
        return [
            [['sum'], 'number'],
            [['type'], 'integer'],
            [['date', 'comment'], 'string']
        ];
    }

    public function save(){
        $cost = new Cost();

        $cost->setAttributes([
            'costId'    =>  $this->type,
            'costSumm'  =>  $this->sum,
            'costComment'=> $this->comment,
            'date'      =>  \Yii::$app->formatter->asDate(strtotime($this->date), 'php:Y-m-d')
        ], false);

        return $cost->save(false);
    }

    public function attributeLabels()
    {
        return [
            'sum'       =>  'Сумма',
            'type'      =>  'Тип расходов',
            'date'      =>  'Дата',
            'comment'   =>  'Комментарий',
        ];
    }

}