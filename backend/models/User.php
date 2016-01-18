<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 12.11.15
 * Time: 14:05
 */

namespace backend\models;


use app\models\SiteusersPrivacy;
use yii\web\IdentityInterface;

class User extends \yii\db\ActiveRecord implements IdentityInterface{

    protected $permissions = [];

    public $newPassword;
    public $newPassword2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'siteusers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['password', 'lastLoginIP', 'auth_key'], 'string'],
            [['active', 'showInStat'], 'integer'],
            [['lastActivity'], 'safe'],
            [['username'], 'string', 'max' => 60],
            [['name'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'name' => 'Имя',
            'password' => 'Пароль',
            'active' => 'Активный пользователь',
            'showInStat' => 'Отображать в статистике',
            'lastLoginIP' => 'Последний IP',
            'lastActivity' => 'Последняя активность',
            'auth_key' => 'Auth Key',
            'default_route' => 'Стандартный путь',
            'phone' => 'Телефон',
            'birthdate' => 'День рождения',
            'avatar' => 'Аватар',
            'workStatus' => 'Статус',
            'tasksUser' => 'Есть в задачах',
            'tasksRole' => 'Роль в задачах',
            'newPassword' => 'Новый пароль',
            'newPassword2' => 'Повторите новый пароль',
        ];
    }

    public function beforeSave($input){
        if($this->password != $this->oldAttributes['password']){
            $this->password = hash("sha512", $this->password, false);
        }

        return parent::beforeSave($input);
    }

    public function afterFind(){
        /*$this->lastLoginIP = \Yii::$app->request->getUserIP();
        $this->lastActivity = date('Y-m-d H:i:s');

        $this->save();*/

        return parent::afterFind();
    }

    public static function findIdentity($id){
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null){
        return static::findOne(['auth_key' => $token]);
    }

    public static function findByUsername($username){
        return static::findOne(['username' => $username]);
    }

    public function getId(){
        return $this->getPrimaryKey();
    }

    public function getAuthKey(){
        return $this->auth_key;
    }

    public function validateAuthKey($authKey){
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password){
        return $this->password === hash("sha512", $password, false);
    }

    public function setPassword($password){
        $this->password = hash("sha512", $password, false);
    }

    public function can($level){
        if($this->superAdmin == 1){
            return true;
        }

        if(empty($this->permissions)){
            foreach(SiteusersPrivacy::find()->where(['userID' => $this->getId()])->each() as $userPrivacy){
                $this->permissions[$userPrivacy->controller][$userPrivacy->action] = $userPrivacy->level;
            }
        }

        $permissionLevel = 0;

        if(isset($this->permissions[\Yii::$app->controller->className()]) && isset($this->permissions[\Yii::$app->controller->className()][\Yii::$app->controller->action->id])){
            $permissionLevel = $this->permissions[\Yii::$app->controller->className()][\Yii::$app->controller->action->id];
        }

        if(is_array($level)){
            return $level[$permissionLevel];
        }else{
            return $permissionLevel >= $level;
        }
    }


}