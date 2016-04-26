<?php
namespace frontend\models;

use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $name;
    public $surname;
    public $email;
    public $password;
    public $phone;
    public $captcha;
    public $newCustomer = true;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],

            ['surname', 'filter', 'filter' => 'trim'],
            ['surname', 'required'],
            ['surname', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\frontend\models\User', 'message' => \Yii::t('shop', 'Пользователь с таким аддресом электронной почты уже зарегистрирован!')],

            ['phone', 'filter', 'filter' => 'trim'],
            ['phone', 'required'],
            ['phone', 'udokmeci\yii2PhoneValidator\PhoneValidator','strict'=>false,'format'=>true],
            ['phone', 'string', 'max' => 255],
            ['phone', 'unique', 'targetClass' => '\frontend\models\User', 'message' => \Yii::t('shop', 'Пользователь с таким номером телефона уже зарегистрирован!')],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['newCustomer', 'required'],
            ['newCustomer', 'boolean'],

            ['captcha', 'required'],
            ['captcha', 'captcha', 'captchaAction'  =>  'site/captcharegistermodal'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();

            if($this->newCustomer){
                $user->Company = $this->name.' '.$this->surname;
                $user->phone = $this->phone;
                $user->email = $this->email;
                /*
                $user->name = $this->name;
                $user->surname = $this->surname;
                 */
            }else{
                $user = User::findOne(['email' => $this->email, 'phone' => $this->phone]);
            }

            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save(false)) {
                return $user;
            }
        }

        \Yii::trace($this->getErrors());

        return null;
    }

    public function attributeLabels(){
        return [
            'name'              =>  \Yii::t('shop', 'Ваше Имя'),
            'surname'           =>  \Yii::t('shop', 'Ваша Фамилия'),
            'email'             =>  \Yii::t('shop', 'Ваш email'),
            'password'          =>  \Yii::t('shop', 'Ваш пароль'),
            'phone'             =>  \Yii::t('shop', 'Ваш телефон'),
            'captcha'           =>  \Yii::t('shop', 'Капча'),
        ];
    }
}
