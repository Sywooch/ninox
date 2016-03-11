<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 07.12.15
 * Time: 17:07
 */

namespace backend\models;


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

    public function behaviors(){
        return [
            'LoggableBehavior' => [
                'class' => 'sammaye\audittrail\LoggableBehavior',
                'ignored' => [
                    'Name2',
                    'ID'
                ],
            ]
        ];
    }

}