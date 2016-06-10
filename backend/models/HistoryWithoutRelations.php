<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 09.06.16
 * Time: 18:21
 */

namespace backend\models;


class HistoryWithoutRelations extends History
{

    public function getID(){
        return $this->id;
    }

}