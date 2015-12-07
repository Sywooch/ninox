<?php

namespace frontend\models;

use Yii;

class Cart extends \common\models\Cart
{

    public function getItemsQuery(){
        return Good::find()
            ->where(['in', 'id',
                self::find()
                    ->select('goodId')
                    ->where(['cartCode' => \Yii::$app->cart->cartCode])]);
    }

    public function getItems(){
        return $this->getItemsQuery()->all();
    }
}
