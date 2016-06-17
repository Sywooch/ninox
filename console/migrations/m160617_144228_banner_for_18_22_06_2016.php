<?php

use common\models\Banner;
use yii\db\Migration;

class m160617_144228_banner_for_18_22_06_2016 extends Migration
{
    public function up()
    {
        $images = [
            [
                'value'     =>  '/img/banners/18-21_06_2016.png',
                'link-ru'   =>  '/rukodelie',
                'link-uk'   =>  '/rukodillya',
            ]
        ];

        foreach($images as $image){
            $banner = new Banner([
                'category'  =>  29,
                'ID'        =>  (Banner::find()->select("MAX(ID)")->scalar() + 1),
                'type'      =>  Banner::TYPE_IMAGE,
                'dateFrom'  =>  '2016-06-18 00:00:00',
                'dateTo'    =>  '2016-06-21 23:59:59',
            ]);

            $bannerTranslation = new \common\models\BannerTranslation([
                'ID'        =>  $banner->ID,
                'state'     =>  1,
                'value'     =>  $image['value'],
                'link'      =>  isset($image['link-ru']) ? $image['link-ru'] : '',
                'language'  =>  'ru-RU'
            ]);

            $bannerTranslationUK = new \common\models\BannerTranslation([
                'ID'        =>  $banner->ID,
                'state'     =>  1,
                'value'     =>  $image['value'],
                'link'      =>  isset($image['link-uk']) ? $image['link-uk'] : '',
                'language'  =>  'uk-UA'
            ]);

            if($bannerTranslation->save(false) && $bannerTranslationUK->save(false)){
                $banner->save(false);
            }
        }
    }

    public function down()
    {
        echo "m160617_144228_banner_for_18_22_06_2016 cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
