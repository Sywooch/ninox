<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 21.03.16
 * Time: 18:27
 */

namespace frontend\models;

class BannersCategory extends \common\models\BannersCategory
{

    public function getBanners(){
        return Banner::find()->where(['category' => $this->id])->orderBy('order ASC')->all();
    }

}