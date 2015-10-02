<?php

namespace common\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "siteusers".
 *
 * @property integer $id
 * @property string $username
 * @property string $name
 * @property string $password
 * @property integer $active
 * @property integer $showInStat
 * @property string $lastLoginIP
 * @property string $lastActivity
 * @property string $auth_key
 * @property string $default_route
 * @property string $phone
 * @property string $birthdate
 * @property string $avatar
 * @property integer $workStatus
 * @property integer $tasksUser
 * @property integer $tasksRole
 */
class Siteuser extends \yii\db\ActiveRecord
{
    public static $siteusers;
    public $user_role = null;

    public static $workStatuses = [
        '0' =>  'Свободен',
        '1' =>  'Занят',
        '2' =>  'Нет на месте',
        '3' =>  'Не на работе',
        '4' =>  'В отпуску',
    ];


    public static function getUser($id){
        if(!empty(self::$siteusers)){
            return self::$siteusers[$id];
        }

        $m = self::find()->all();

        foreach($m as $user){
            self::$siteusers[$user->id] = $user;
        }

        return self::getUser($id);
    }

    public static function getActiveUsers(){
        $m = self::findAll(['active' => 1]);
        $users = [];

        $users[] = 'Не выбрано';

        foreach($m as $u){
            $users[$u->id] = $u->name;
        }

        return $users;
    }

    public static function getCollectorsWithData($timeFrom = null, $timeTo = null){
        $timeFrom = $timeFrom == null ? (time() - (date('H') * 3600 + date('i') * 60 + date('s'))) : $timeFrom;

        $collectors = $collectorsIDs = [];

        foreach(Siteuser::findAll(['active' => 1]) as $activeUser){
            $collectorsIDs[] = $activeUser->id;

            $collectors[$activeUser->id] = [
                'name'              =>  $activeUser->name,
                'userID'            =>  $activeUser->id,
                'totalOrders'       =>  0,
                'completedOrders'   =>  0
            ];
        }

        $orders = new Query();

        $orders = $orders
            ->select(['COUNT(id) as totalOrders', 'SUM(done) as completedOrders', 'responsibleUserID'])
            ->from(History::tableName())
            ->where(['in', 'responsibleUserID', $collectorsIDs])
            ->andWhere('added >= '.$timeFrom)
            ->andWhere(['deleted' => 0])
            ->groupBy(['responsibleUserID']);

        if($timeTo != null){
            $orders->andWhere('added <= '.$timeTo);
        }

        foreach($orders->each() as $row){
            $collectors[$row['responsibleUserID']]['totalOrders'] = $row['totalOrders'];
            $collectors[$row['responsibleUserID']]['completedOrders'] = $row['completedOrders'];
        }

        sort($collectors);

        return $collectors;
    }

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
            [['password', 'lastLoginIP', 'auth_key', 'default_route'], 'string'],
            [['active', 'showInStat', 'workStatus', 'tasksUser', 'tasksRole'], 'integer'],
            ['tasksRole', 'default', 'value' => 0],
            [['lastActivity', 'birthdate'], 'safe'],
            [['username'], 'string', 'max' => 60],
            [['name'], 'string', 'max' => 20],
            [['phone', 'avatar'], 'string', 'max' => 255],
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
        ];
    }

    public function beforeSave($input){
        if(!isset($this->oldAttributes['password']) || $this->password != $this->oldAttributes['password']){
            $this->password = hash("sha512", $this->password, false);
        }

        return parent::beforeSave($input);
    }
}
