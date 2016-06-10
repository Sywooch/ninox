<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "shops".
 *
 * @property integer $id
 * @property string $name
 * @property integer daySalesPlan
 */
class Shop extends \yii\db\ActiveRecord
{

    const TYPE_STORE = 1;
    const TYPE_WAREHOUSE = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shops';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'type', 'daySalesPlan'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function getMonthPlan($month = null, $year = null){
        if(empty($year)){
            $year = date('Y');
        }

        if(empty($month)){
            $month = date('m');
        }

        return $this->daySalesPlan * cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'name' => Yii::t('common', 'Name'),
            'type' => Yii::t('common', 'Type'),
        ];
    }
}
