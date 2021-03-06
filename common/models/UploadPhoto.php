<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 07.05.15
 * Time: 17:35
 */

namespace common\models;


use yii\base\Model;
use yii\web\UploadedFile;

class UploadPhoto extends Model{
    /**
     * @var UploadedFile file attribute
     */
    public $file;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['file'], 'file'],
        ];
    }
}