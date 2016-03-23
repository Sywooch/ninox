<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 03.02.16
 * Time: 14:08
 */

namespace cashbox\models;


class Siteuser extends \common\models\Siteuser{

    public static function find(){
        $parent = parent::find();

        /*if(!empty(\Yii::$app->params['allowedUsers'])){
            $parent->andWhere(['in', 'id', \Yii::$app->params['allowedUsers']]);
        }*/

        return $parent;
    }

}