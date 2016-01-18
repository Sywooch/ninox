<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "siteusersPrivacy".
 *
 * @property integer $userID
 * @property string $controller
 * @property string $action
 * @property integer $level
 */
class SiteusersPrivacy extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'siteusersPrivacy';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userID', 'controller', 'action'], 'required'],
            [['userID', 'level'], 'integer'],
            [['controller', 'action'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userID' => 'User ID',
            'controller' => 'Controller',
            'action' => 'Action',
            'level' => 'Level',
        ];
    }
}
