<?php

use common\models\Banner;
use yii\db\Migration;

class m160609_065723_banner_for_child_sale extends Migration
{
    public function up()
    {
        $images = [
            [
                'value' =>  '/img/banners/34qtsedryh.png',
                'link-ru' =>  '/bizhuteriya/detskaya-bizhuteriya',
                'link-uk' =>  '/bizhuteriya/dytyacha-bizhuteriya',
            ]
        ];

        foreach($images as $image){
            $banner = new Banner([
                'category'  =>  29,
                'ID'        =>  (Banner::find()->select("MAX(ID)")->scalar() + 1),
                'type'      =>  Banner::TYPE_IMAGE,
                'dateFrom'  =>  '2016-06-09 00:00:00',
                'dateTo'    =>  '2016-06-12 23:59:59',
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
        echo "m160609_065723_banner_for_child_sale cannot be reverted.\n";
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
