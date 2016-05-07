<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 27.04.16
 * Time: 17:40
 */

namespace common\models;


use yii\db\ActiveRecord;

class CategoryTranslation extends ActiveRecord
{

    public static function tableName(){
        return 'category_translations';
    }

    public function afterFind(){
        $this->link = urldecode($this->link);

        return parent::afterFind();
    }

}