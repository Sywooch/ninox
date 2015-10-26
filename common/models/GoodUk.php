<?php

namespace common\models;

use common\helpers\TranslitHelper;
use Yii;

/**
 * This is the model class for table "goods_uk".
 *
 * @property integer $ID
 * @property string $Name
 * @property string $Name2
 * @property string $Description
 * @property string $link
 */
class GoodUk extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_uk';
    }

    public function behaviors()
    {
        return [
            'LoggableBehavior' => [
                'class' => 'sammaye\audittrail\LoggableBehavior',
                'options'   =>  [
                    'discardSaveCreate' =>  true,
                    'model'     =>  Good::className()
                ],
                'ignored' => [
                    'Name2'
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['link'], 'string'],
            [['Name', 'Name2'], 'string', 'max' => 255],
            [['Description'], 'string', 'max' => 2550]
        ];
    }

    public function beforeSave(){
        $this->link = TranslitHelper::to($this->Name);

        return parent::beforeSave(false);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'Name' => 'Name',
            'Name2' => 'Name2',
            'Description' => 'Description',
            'link' => 'Link',
        ];
    }
}
