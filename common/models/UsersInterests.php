<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 23.04.16
 * Time: 16:16
 */
namespace common\models;

use Yii;

/**
 * This is the model class for table "users_interests".
 *
 * @property string $name
 * @property string $email
 * @property string $text
 */

class UsersInterests extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'users_interests';
    }

    public function rules()
    {
        return [
            [['name', 'email', 'text'], 'required'],
            [['name', 'email'], 'string', 'max' => 30],
            [['text'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'name',
            'email'=> 'email',
            'text' => 'text',
        ];
    }

}