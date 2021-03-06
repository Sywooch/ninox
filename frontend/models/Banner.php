<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 24.11.15
 * Time: 14:46
 */

namespace frontend\models;

class Banner extends \common\models\Banner{

    public $format;


    public function afterFind()
    {
        $this->format = $this->type == 'image' ? self::TYPE_IMAGE : self::TYPE_HTML;

        return parent::afterFind(); // TODO: Change the autogenerated stub
    }

    public function getCategory(){
        return $this->hasOne(BannersCategory::className(), ['category' => 'ID']);
    }

}
