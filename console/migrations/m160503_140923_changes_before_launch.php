<?php

use common\models\Banner;
use common\models\BannerTranslation;
use yii\db\Migration;

class m160503_140923_changes_before_launch extends Migration
{
    /**
     *
     */
    public function up()
    {
        \common\models\CategoryTranslation::updateAll(['enabled' => 0], ['in', 'ID', \common\models\Category::find()->select('ID')->where(['in', 'Code', ['ABN', 'ABJ']])]);
        \common\models\BannersCategory::updateAll(['maxDisplayed'   =>  4], ['alias'    =>  '2x2']);

        $banners = [
            '2x2'   =>  [
                'type'  =>  Banner::TYPE_IMAGE,
                'items' =>  [
                    [
                        'value' =>  '/img/banners/2033932.png',
                        'link'  =>  '/tovar/-g33932'
                    ],[
                        'value' =>  '/img/banners/2038644.png',
                        'link'  =>  '/tovar/-g38644'
                    ],[
                        'value' =>  '/img/banners/2051730.png',
                        'link'  =>  '/tovar/-g51730'
                    ],[
                        'value' =>  '/img/banners/fen.png',
                        'link'  =>  '/tovar/fen-moser-ventus-sw-s-ionizaciey-i-turmalinom-2200w-4350-0050-g44223'
                    ],
                ]
            ],
            '1x1.1' =>  [
                'type'  =>  Banner::TYPE_GOOD,
                'items' =>  [
                    [
                        'value' =>  '15',
                    ],
                ]
            ],
            '1x1.2' =>  [
                'type'  =>  Banner::TYPE_GOOD,
                'items' =>  [
                    [
                        'value' =>  '20',
                    ],
                ]
            ],
            '1x1.3' =>  [
                'type'  =>  Banner::TYPE_IMAGE,
                'items' =>  [
                    [
                        'value' =>  '/img/banners/vystavka.gif',
                        'link'  =>  ''
                    ],
                ]
            ],
            '1x1.4' =>  [
                'type'  =>  Banner::TYPE_IMAGE,
                'items' =>  [
                    [
                        'value' =>  '/img/banners/chicco_logo.png',
                        'link'  =>  '/tovary-dlya-detey'
                    ],
                ]
            ],
            /*'1x2'   =>  [
                'type'  =>  '',
                'items' =>  [
                    [
                        'value' =>  '',
                        'link'  =>  ''
                    ],
                ]
            ]*/
        ];

        foreach($banners as $alias => $bannerGroup){
            $bannerCategory = \common\models\BannersCategory::findOne(['alias' => $alias]);

            if($bannerCategory) {
                Banner::updateAll(['deleted' => 1], ['category' => $bannerCategory->id]);

                foreach($bannerGroup['items'] as $bannerItem){
                    $lastBannerTranslateID = (BannerTranslation::find()->max("ID")) + 1;

                    $banner = new Banner([
                        'category'  =>  $bannerCategory->id,
                        'type'      =>  $bannerGroup['type'],
                        'ID'        =>  $lastBannerTranslateID
                    ]);

                    $bannerTranslation = new BannerTranslation([
                        'ID'        =>  $banner->ID,
                        'state'     =>  BannerTranslation::STATE_ENABLED,
                        'value'     =>  $bannerItem['value'],
                        'language'  =>  'ru_RU'
                    ]);

                    if(!empty($bannerItem['link'])){
                        $bannerTranslation->link = $bannerItem['link'];
                    }

                    $bannerTranslation->save(false);

                    $banner->save(false);
                }
            }
        }
    }

    public function down()
    {
        echo "m160503_140923_changes_before_launch cannot be reverted.\n";

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
