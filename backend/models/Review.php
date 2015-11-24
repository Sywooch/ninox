<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 24.11.15
 * Time: 14:40
 */

namespace backend\models;


class Review extends \common\models\Review{

    public static function changeState($id, $field = 'published'){
        $a = Review::findOne(['id' => $id]);

        if ($a) {
            /*
            if ($field == 'published') {
                $a->published = $a->published == "1" ? "0" : "1";
                $a->save(false);
                return $a->published;
            } else {
                $a->deleted = $a->deleted == "1" ? "0" : "1";
                $a->save(false);
                return $a->deleted;
            }*/
            $a->$field = $a->$field == 1 ? 0 : 1;
            $a->save(false);
            return $a->$field;
        }

        return 0;
    }

}