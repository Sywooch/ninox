<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 29.02.16
 * Time: 16:32
 */

namespace backend\modules\goods\models;


use yii\base\Model;

class GoodAttributesForm extends Model
{

    public $dimensions;
    public $width;
    public $height;
    public $length;
    public $diameter;
    public $measure;

    public function loadGood($good){
        foreach($this->modelAttributes() as $new => $old){
            if(is_int($new)){
                $new = $old;
            }

            $this->$new = $good->$old;
        }
    }

    public function modelAttributes(){
        return [
            'dimensions',
            'width',
            'height',
            'length',
            'diameter',
            'measure'
        ];
    }

    public function attributeLabels(){

    }
}