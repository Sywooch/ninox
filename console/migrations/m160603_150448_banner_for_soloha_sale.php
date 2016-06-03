<?php

use common\models\Banner;
use yii\db\Migration;

class m160603_150448_banner_for_soloha_sale extends Migration
{
    public function up()
    {
        $images = [
            [
                'value' =>  '/img/banners/soloha-sale.png',
                'link' =>  '/bizhuteriya'
            ]
        ];

        foreach($images as $image){
            $banner = new Banner([
                'category'  =>  29,
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

            $bannerTranslationUK = new \common\models\BannerTranslation([
                'ID'        =>  $banner->ID,
                'state'     =>  1,
                'value'     =>  $image['value'],
                'link'      =>  isset($image['link']) ? $image['link'] : '',
                'language'  =>  'uk-UA'
            ]);

            if($bannerTranslation->save(false) && $bannerTranslationUK->save(false)){
                $banner->save(false);
            }
        }
    }

    public function down()
    {
        echo "m160603_150448_banner_for_soloha_sale cannot be reverted.\n";

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
