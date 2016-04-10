<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 10.04.16
 * Time: 17:23
 *
 * Модель формы запроса на перезвон
 */

namespace frontend\models;


use common\models\Callback;
use yii\base\Model;

class CallbackForm extends Model
{

    /**
     * @type string
     */
    public $phone;

    /**
     * @type string
     */
    public $question;

    /**
     * @type string
     */
    public $name;

    public function rules()
    {
        return [
            [['phone', 'question', 'name'], 'string']
        ];
    }

    public function save(){
        $callback = new Callback([
            'phone'         =>  $this->phone,
            'question'      =>  $this->question,
            'customerName'  =>  $this->name,
            'did_callback'  =>  0
        ]);

        $callback->save(false);
    }

}