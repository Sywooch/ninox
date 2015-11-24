<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 24.11.15
 * Time: 14:44
 */

namespace frontend\models;


class Review extends \common\models\Review{

    public static function getReviews(){
        return self::find()->where(['published' => 1])->
        orderBy('type ASC, IF (type = 1, - UNIX_TIMESTAMP(`date`) , UNIX_TIMESTAMP(`date`)) ASC')->all();
    }

}