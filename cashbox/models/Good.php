<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 31.01.16
 * Time: 15:56
 */

namespace cashbox\models;


use common\helpers\PriceHelper;
use common\models\ShopGood;

class Good extends \common\models\Good{

    use PriceHelper;

    public $storeCount;
    public $previousStoreCount;
    public $changedCount = 0;

    public function beforeSave($insert){
        $this->changedCount = $this->getOldAttribute('count') - $this->count;

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes){
        if(isset($changedAttributes['count']) && !empty(\Yii::$app->params['configuration'])){
            $good = ShopGood::findOne(['shopID' => \Yii::$app->params['configuration']->store, 'itemID' => $this->ID]);

            if($good){
                $good->count -= $this->changedCount;
                $good->save(false);
            }
        }

        return parent::afterSave($insert, $changedAttributes);
    }

    public function afterFind(){
        if(!empty(\Yii::$app->params['configuration'])){
            $this->storeCount = ShopGood::find()->select('count')->where(['shopID' => \Yii::$app->params['configuration']->store]);
            $this->previousStoreCount = $this->storeCount;
        }

        return parent::afterFind();
    }

}
