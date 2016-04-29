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
    public $countryCode;
    public $captcha;
    public $newCustomer = true;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'surname', 'email', 'phone', 'password', 'captcha'], 'required'],
            [['countryCode'], 'safe'],
            [['name', 'surname', 'email', 'phone'], 'filter', 'filter' => 'trim'],
            ['name', 'string', 'min' => 2, 'max' => 255],

            ['surname', 'string', 'min' => 2, 'max' => 255],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\frontend\models\User', 'message' => \Yii::t('shop', 'Пользователь с таким аддресом электронной почты уже зарегистрирован!')],

            ['phone', 'udokmeci\yii2PhoneValidator\PhoneValidator', 'countryAttribute' => 'countryCode', 'message' => \Yii::t('shop', 'Введите корректный номер телефона!')],
            ['phone', 'unique', 'targetClass' => '\frontend\models\User', 'message' => \Yii::t('shop', 'Пользователь с таким номером телефона уже зарегистрирован!')],

            ['password', 'string', 'min' => 6],
            ['newCustomer', 'boolean'],
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
