<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 29.02.16
 * Time: 16:28
 */

namespace backend\modules\goods\models;


use yii\base\Model;

class GoodExportForm extends Model
{

    public function loadGood($good){
        foreach($this->modelAttributes() as $new => $old){
            $this->$new = $good->$old;
        }
    }

    public function modelAttributes(){
        return [];
    }

    public function attributeLabels(){
        return [];
    }

}