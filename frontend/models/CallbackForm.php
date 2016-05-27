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
    public $question = '';

    /**
     * @type string
     */
    public $name = '';

    /**
     * @type string
     */
    public $captcha;

    public function rules()
    {
        return [
            ['captcha', 'captcha'],
            [['phone'], 'required'],
            [['phone', 'question', 'name'], 'string'],
            [['question', 'name'], 'default', 'value' => ''],
        ];
    }

    public function save(){
        $callback = new Callback();

        $callback->setAttributes([
            'phone'         =>  $this->phone,
            'question'      =>  $this->question,
            'customerName'  =>  $this->name,
            'did_callback'  =>  0,
        ], false);

        $callback->save(false);
    }

    public function attributeLabels(){
        return [
            'phone'           =>  \Yii::t('shop', 'Телефон:'),
            'question'        =>  \Yii::t('shop', 'Сообщение'),
            'name'            =>  \Yii::t('shop', 'Имя и фамилия'),
            'captcha'         =>  \Yii::t('shop', 'Введите код с картинки'),
        ];
    }

}