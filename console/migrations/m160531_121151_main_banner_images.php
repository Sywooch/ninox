<?php

use yii\db\Migration;

class m160531_121151_main_banner_images extends Migration
{
    public function up()
    {
        $banner = \common\models\BannerTranslation::findOne(['ID' => 491]);
        if($banner){
            $banner->value = '/img/banners/fen.png';
            $banner->save(false);
        }

        $banner = \common\models\BannerTranslation::findOne(['ID' => 491]);
        if($banner){
            $banner->link = '/tovar/fen-moser-ventus-sw-s-ionizaciey-i-turmalinom-2200w-4350-0050-g44223';
            $banner->save(false);
        }

        $banner = \common\models\BannerTranslation::findOne(['ID' => 494]);
        if($banner){
            $banner->value = '/img/banners/2033932.png';
            $banner->save(false);
        }
        $banner =
            \common\models\BannerTranslation::findOne(['ID' => 494]);
        if($banner){
            $banner->link = '/tovar/-g33932';
            $banner->save(false);
        }
    }

    public function down()
    {
        $banner = \common\models\BannerTranslation::findOne(['ID' => 494]);
        if($banner){
            $banner->value = '/img/banners/fen.png';
            $banner->save(false);
        }

        $banner = \common\models\BannerTranslation::findOne(['ID' => 494]);
        if($banner){
            $banner->link = '/tovar/fen-moser-ventus-sw-s-ionizaciey-i-turmalinom-2200w-4350-0050-g44223';
            $banner->save(false);
        }

        $banner = \common\models\BannerTranslation::findOne(['ID' => 491]);
        if($banner){
            $banner->value = '/img/banners/2033932.png';
            $banner->save(false);
        }
        $banner =
            \common\models\BannerTranslation::findOne(['ID' => 491]);
        if($banner){
            $banner->link = '/tovar/-g33932';
            $banner->save(false);
        }
        echo "m160531_121151_main_banner_images cannot be reverted.\n";

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
