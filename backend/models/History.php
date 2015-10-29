<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 29.10.15
 * Time: 17:45
 */

namespace backend\models;


class History extends \common\models\History{

    public function beforeSave($insert){
        if($this->oldAttributes['confirmed'] != $this->confirmed && $this->confirmed == 1){
            //$this->confirmDate = date('Y-m-d H:i:s');
        }
        return parent::beforeSave($insert);
    }

    public function behaviors()
    {
        return [
            'LoggableBehavior' => [
                'class' => 'sammaye\audittrail\LoggableBehavior',
                'ignored' => [
                    'Name2',
                    'displayorder'
                ],
            ]
        ];
    }

}