<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 24.11.15
 * Time: 14:42
 */

namespace frontend\models;


class Question extends \common\models\Question{

    public static function getQuestions()
    {
        return self::find()->where(['published' => 1])->
        andWhere(['domainId' => 1])->all(); //TODO: айди домена нужно подставлять динамически.
    }

}