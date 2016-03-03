<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 07.12.15
 * Time: 17:07
 */

namespace backend\models;


use common\models\Category;

class Good extends \common\models\Good{

    public static function changeTrashState($id){
        $a = self::findOne(['ID' => $id]);

        if($a){
            $a->Deleted = $a->Deleted == "1" ? "0" : "1";
            $a->save(false);

            return $a->Deleted;
        }

        return false;
    }

    public function getPhotos(){
        return GoodPhoto::find()->where(['itemid' => $this->ID])->orderBy('order')->all();
    }

    public function getCategory(){
        return Category::findOne($this->GroupID);
    }

}