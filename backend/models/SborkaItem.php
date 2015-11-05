<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 30.10.15
 * Time: 13:49
 */

namespace backend\models;


class SborkaItem extends \common\models\SborkaItem{

    public $priceModified = false;

    public function behaviors()
    {
        return [
            'LoggableBehavior' => [
                'class' => 'sammaye\audittrail\LoggableBehavior',
                'ignored' => [
                    'added',
                ],
            ]
        ];
    }

}