<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 04.04.16
 * Time: 17:34
 */

namespace backend\models;


use yii\base\Model;

class NovaPoshtaModels extends Model
{

    public function isHash($hash){
        return preg_match('/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/', $hash) ? true : false;
    }

}