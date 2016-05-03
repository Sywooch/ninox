<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 22.03.16
 * Time: 18:00
 */

namespace backend\models;


use common\models\Banner;

class BannersCategory extends \common\models\BannersCategory
{
    private $_banners = [];

    /**
     * @return Banner[]
     */
    public function getBanners(){
        if(!empty($this->_banners)){
            return $this->_banners;
        }

        $banners = Banner::find()
            ->where(['category' => $this->id, 'deleted' => 0])
            ->andWhere(['or', 'dateFrom <= :date', "`dateFrom` = '0000-00-00 00:00:00'"], [
                'date'  =>  date('Y-m-d H:i:s')
            ])
            ->andWhere(['or', 'dateTo >= :date', "`dateTo` = '0000-00-00 00:00:00'"], [
                'date'  =>  date('Y-m-d H:i:s')
            ])
            ->orderBy('order ASC');

        if(!empty($this->maxDisplayed)){
            $banners->limit($this->maxDisplayed);
        }

        return $this->_banners = $banners->all();
    }

}