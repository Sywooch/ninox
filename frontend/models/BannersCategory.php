<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 21.03.16
 * Time: 18:27
 */

namespace frontend\models;

use common\models\BannerTranslation;

class BannersCategory extends \common\models\BannersCategory
{

    private $_banners = [];

    /**
     * @return Banner[]
     */
    public function getBanners(){
        $relation = $this->hasMany(Banner::className(), ['category' => 'id'])
            ->andWhere(['deleted' => 0])
            ->andWhere(['or', 'dateFrom <= :date', "`dateFrom` = '0000-00-00 00:00:00'"], [
                'date'  =>  date('Y-m-d H:i:s')
            ])
            ->andWhere(['or', 'dateTo >= :date', "`dateTo` = '0000-00-00 00:00:00'"], [
                'date'  =>  date('Y-m-d H:i:s')
            ])->leftJoin(BannerTranslation::tableName(), BannerTranslation::tableName().'.ID = '.Banner::tableName().'.ID')
            ->andWhere(BannerTranslation::tableName().'.state = 1')
            ->andWhere(BannerTranslation::tableName().'.language = \''.\Yii::$app->language.'\'')
            ->orderBy('order ASC');

        if(!empty($this->maxDisplayed)){
            $relation->limit($this->maxDisplayed);
        }

        return $relation;

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
            ])->leftJoin(BannerTranslation::tableName(), BannerTranslation::tableName().'.ID = '.Banner::tableName().'.ID')
            ->andWhere(BannerTranslation::tableName().'.state = 1')
            ->andWhere(BannerTranslation::tableName().'.language = \''.\Yii::$app->language.'\'')
            ->orderBy('order ASC');



        return $this->_banners = $banners->all();
    }

}