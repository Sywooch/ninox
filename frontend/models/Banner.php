<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 24.11.15
 * Time: 14:46
 */

namespace frontend\models;


use yii\base\InvalidConfigException;

class Banner extends \common\models\Banner{

    public $format;

    public function afterFind()
    {
        $this->format = $this->type == 'image' ? self::TYPE_IMAGE : self::TYPE_HTML;

        return parent::afterFind(); // TODO: Change the autogenerated stub
    }

    /**
     * @deprecated use /frontend/models/BannersCategory->banners
     * @param $alias
     * @param bool $all
     *
     * @return array|null|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]
     * @throws \yii\base\InvalidConfigException
     */
    public static function getByAlias($alias, $all = true){
        if(empty($alias)){
            throw new InvalidConfigException('Тип баннера не может быть пустым!');
        }

        $date = date('Y-m-d H:i:s');

        $q = self::find()
            ->where(['banners_type.alias' => $alias, 'banners.state' => '1'])
            ->andWhere(['or', 'banners.dateFrom <= :date', 'banners.dateFrom = \'0000-00-00 00:00:00\''], [
                ':date'  =>  $date
            ])
            ->andWhere(['or', 'banners.dateTo >= \':date\'', 'banners.dateTo = \'0000-00-00 00:00:00\''], [
                ':date' => $date
            ])
            ->andWhere('banners.banner_ru != \'\'')
            ->leftJoin('banners_type', 'banners_type.id = banners.bannerTypeId')
            ->orderBy('banners.bannerOrder');

        return $all ? $q->all() : $q->one();
    }

}
