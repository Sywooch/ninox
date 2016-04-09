<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 09.04.16
 * Time: 16:28
 */

namespace backend\models;


use yii\base\Model;

class NovaPoshtaSeat extends Model
{

    /**
     * @type integer - Объём отправления
     */
    public $volumetricVolume;

    /**
     * @type integer - Ширина отправления
     */
    public $volumetricWidth;

    /**
     * @type integer - Длина отправления
     */
    public $volumetricLength;

    /**
     * @type integer - Высота отправления
     */
    public $volumetricHeight;

    /**
     * @type integer - Фактический вес
     */
    public $weight;

    public function attributeLabels()
    {
        return [
            'volumetricVolume'  =>  'Объём',
            'volumetricWidth'   =>  'Ширина',
            'volumetricHeight'  =>  'Высота',
            'volumetricLength'  =>  'Глубина',
            'weight'            =>  'Вес',
        ];
    }

}