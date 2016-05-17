<?php

use common\models\Banner;
use yii\db\Migration;

class m160506_154449_banners_for_index extends Migration
{
    public function up()
    {
        $images = [
            [
                'value' =>  '/img/banners/1.png'
            ]
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
        echo "m160506_154449_banners_for_index cannot be reverted.\n";

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
