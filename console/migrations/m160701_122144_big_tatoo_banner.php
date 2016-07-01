<?php

use common\models\Banner;
use yii\db\Migration;

class m160701_122144_big_tatoo_banner extends Migration
{
    public function up()
    {
        $images = [
            [
                'value'     =>  '/img/banners/tatoo2.png',
                'link-ru'   =>  '/bizhuteriya/perevodnye-tatuirovki',
                'link-uk'   =>  '/bizhuteriya/perevodnye-tatuirovki',
            ]
        ];

        foreach($images as $image){
            $banner = new Banner([
                'category'  =>  29,
                'ID'        =>  Banner::find()->max('id') + 1,
                'type'      =>  Banner::TYPE_IMAGE,
                'dateFrom'  =>  '2016-07-01 00:00:00',
                'dateTo'    =>  '2016-07-08 23:59:59',
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
        echo "m160701_122144_big_tatoo_banner cannot be reverted.\n";

        return true;
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
