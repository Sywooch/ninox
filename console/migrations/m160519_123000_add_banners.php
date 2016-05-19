<?php

use frontend\models\Banner;
use yii\db\Migration;

class m160519_123000_add_banners extends Migration
{
    public function up()
    {
        $images = [
            [
                'value' =>  '/img/banners/1.png',
                'link'  =>  '/bizhuteriya/yuvelirnaya-bizhuteriya'
            ],[
                'value' =>  '/img/banners/begovel.jpg',
                'link'  =>  '/tovary-dlya-detey/detskiy-transport/velosipedy'
            ],[
                'value' =>  '/img/banners/rukodelie.jpg',
                'link'  =>  '/rukodelie'
            ],
        ];

        $bannerCategory = \common\models\BannersCategory::find()->select('id')->where(['alias' => 'slider_v3'])->scalar();

        Banner::updateAll(['deleted' => 1], ['category' => $bannerCategory]);

        foreach($images as $image){
            $banner = new Banner([
                'category'  =>  $bannerCategory,
                'ID'        =>  (Banner::find()->select("MAX(ID)")->scalar() + 1),
                'type'      =>  Banner::TYPE_IMAGE
            ]);

            $bannerTranslation = new \common\models\BannerTranslation([
                'ID'        =>  $banner->ID,
                'state'     =>  1,
                'value'     =>  $image['value'],
                'link'      =>  isset($image['link']) ? $image['link'] : '',
                'language'  =>  'ru-RU'
            ]);

            if($bannerTranslation->save(false)){
                $banner->save(false);
            }
        }
    }

    public function down()
    {
        echo "m160519_123000_add_banners cannot be reverted.\n";

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
