<?php

namespace frontend\models;

use common\models\Good;
use Yii;

/**
 * This is the model class for table "items_rate".
 *
 * @property integer $itemID
 * @property integer $ip
 * @property string $customerID
 * @property integer $rate
 * @property string $date
 */
class ItemRate extends \common\models\ItemRate
{
    public $average = 0;

    public function afterSave($insert, $changedAttributes){
        $this->average = round(self::find()->where(['itemID' => $this->itemID])->average('rate'), 1);
        $item = Good::findOne($this->itemID);
        if($item){
            $item->rate = $this->average;
            $item->save(false);
        }
    }
}
