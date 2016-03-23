<?php

use common\models\BannerTranslation;
use frontend\models\Banner;
use yii\db\Migration;

class m160322_112534_banners_max_count extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\BannersCategory::tableName(), 'maxDisplayed', \yii\db\Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0');

        \frontend\models\BannersCategory::updateAll(['maxDisplayed' => 1], ['in', 'alias', ['2x2', '1x1.1', '1x1.2', '1x1.3', '1x1.4', '1x2']]);

        $testBanners = [
            [
                'value'     =>  '16',
                'type'      =>  Banner::TYPE_GOOD,
                'category'  =>  \common\models\BannersCategory::findOne(['alias' => '1x1.1'])->id
            ],[
                'value'     =>  '<span>**ярмарка мастеров</span>',
                'type'      =>  Banner::TYPE_HTML,
                'category'  =>  \common\models\BannersCategory::findOne(['alias' => '1x1.2'])->id
            ],[
                'value'     =>  '<span>Ярмарка мастеров</span>',
                'type'      =>  Banner::TYPE_HTML,
                'category'  =>  \common\models\BannersCategory::findOne(['alias' => '1x1.3'])->id
            ],[
                'value'     =>  '38780',
                'type'      =>  Banner::TYPE_GOOD,
                'category'  =>  \common\models\BannersCategory::findOne(['alias' => '1x1.4'])->id
            ],[
                'value'     =>  \yii\helpers\Json::encode([
                    'goodID'    =>  12816,
                    'image'     =>  'watch.png'
                ]),
                'type'      =>  Banner::TYPE_GOOD_IMAGE,
                'category'  =>  \common\models\BannersCategory::findOne(['alias' => '1x2'])->id
            ],[
                'value' =>  'http://cs9383.vk.me/u116907141/126495613/x_15bfe53f.jpg',
                'type'      =>  Banner::TYPE_IMAGE,
                'category'  =>  \common\models\BannersCategory::findOne(['alias' => '2x2'])->id
            ],
        ];

        foreach($testBanners as $bannerArray){
            $bannerItem = new BannerTranslation([
                'value'     =>  $bannerArray['value'],
                'link'      =>  'test',
                'language'  =>  \Yii::$app->language,
                'state'     =>  1
            ]);

            if($bannerItem->save(false)){
                $banner = new Banner([
                    'ID'        =>  $bannerItem->ID,
                    'type'      =>  $bannerArray['type'],
                    'category'  =>  $bannerArray['category']
                ]);

                $banner->save(false);
            }
        }
    }

    public function down()
    {
        $this->dropColumn(\common\models\BannersCategory::tableName(), 'maxDisplayed');

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
