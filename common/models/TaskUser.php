<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "task_users".
 *
 * @property integer $id
 * @property integer $task_id
 * @property integer $user_id
 * @property integer $user_role
 */
class TaskUser extends \yii\db\ActiveRecord
{
    public static $roles = [
        '0' =>  '',
        '1' =>  'Наблюдатель',
        '2' =>  'Консультант',
        '3' =>  'Учусь',
        '4' =>  'Помошник',
        '5' =>  'Ответственный'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'user_id', 'user_role'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'        => 'ID',
            'task_id'   => 'Task ID',
            'user_id'   => 'User ID',
            'user_role' => 'User Role',
        ];
    }
}
