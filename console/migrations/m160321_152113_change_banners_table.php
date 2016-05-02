<?php

use frontend\models\Banner;
use yii\db\Migration;
use yii\db\Schema;

class m160321_152113_change_banners_table extends Migration
{
    public function up()
    {
        $this->createTable('banners_translations', [
            'ID'        =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL',
            'state'     =>  Schema::TYPE_SMALLINT.' UNSIGNED NOT NULL DEFAULT 0',
            'value'     =>  Schema::TYPE_TEXT,
            'link'      =>  Schema::TYPE_STRING,
            'language'  =>  Schema::TYPE_STRING
        ]);

        $this->addPrimaryKey('pk_banners_translation_ID_language', 'banners_translations', ['ID', 'language']);

        $this->renameColumn('banners', 'id', 'ID');
        $this->renameColumn('banners', 'bannerTypeId', 'category');
        $this->renameColumn('banners', 'date', 'added');
        $this->renameColumn('banners', 'bannerOrder', 'order');
        $this->renameColumn('banners', 'dateStart', 'dateFrom');
        $this->renameColumn('banners', 'dateEnd', 'dateTo');

        $bannersCount = \common\models\Banner::find()->count();

        echo "    > Updating banner->type. Seem {$bannersCount} banners...\r\n";

        $i = 1;

        foreach(\common\models\Banner::find()->each() as $banner){
            if(!filter_var($banner->type, FILTER_VALIDATE_INT)){
                $banner->type = $banner->type == 'html' ? Banner::TYPE_HTML : ($banner->type == 'image' ? Banner::TYPE_IMAGE : Banner::TYPE_GOOD);

                echo "    > Saving banner {$i} from {$bannersCount}...";

                if($banner->save(false)){
                    echo " Saved!\r\n";
                }
            }

            if(!empty($banner->banner_ru) || !empty($banner->link_ru)){
                $bannerTranslate = new \common\models\BannerTranslation([
                    'ID'        =>  $banner->ID,
                    'state'     =>  $banner->state,
                    'value'     =>  $banner->banner_ru,
                    'link'      =>  $banner->link_ru,
                    'language'  =>  'ru_RU'
                ]);

                $bannerTranslate->save(false);
            }

            if(!empty($banner->banner_uk) || !empty($banner->link_uk)){
                $bannerTranslate = new \common\models\BannerTranslation([
                    'ID'        =>  $banner->ID,
                    'state'     =>  $banner->state,
                    'value'     =>  $banner->banner_uk,
                    'link'      =>  $banner->link_uk,
                    'language'  =>  'uk_UA'
                ]);

                $bannerTranslate->save(false);
            }

            if(!empty($banner->banner_be) || !empty($banner->link_be)){
                $bannerTranslate = new \common\models\BannerTranslation([
                    'ID'        =>  $banner->ID,
                    'state'     =>  $banner->state,
                    'value'     =>  $banner->banner_be,
                    'link'      =>  $banner->link_be,
                    'language'  =>  'be_BY'
                ]);

                $bannerTranslate->save(false);
            }

            $i++;
        }

        echo "     > removing bad banners...\r\n";

        foreach(Banner::find()->where(['banner_ru' => '', 'link_ru' => '', 'banner_uk' => '', 'link_uk' => '', 'banner_be' => '', 'link_be' => ''])->each() as $badBanner){
            $badBanner->delete();
        }

        $this->addForeignKey('fk_banners_banners_translations', 'banners', 'ID', 'banners_translations', 'ID', 'CASCADE', 'RESTRICT');

        $this->alterColumn('banners', 'type', Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0');
        $this->dropColumn('banners', 'banner_ru');
        $this->dropColumn('banners', 'banner_uk');
        $this->dropColumn('banners', 'banner_be');
        $this->dropColumn('banners', 'link_ru');
        $this->dropColumn('banners', 'link_uk');
        $this->dropColumn('banners', 'link_be');
        $this->dropColumn('banners', 'state');
        $this->dropColumn('banners', 'categoryCode');
        $this->dropColumn('banners', 'bg');
    }

    public function down()
    {
        $this->dropForeignKey('fk_banners_banners_translations', 'banners');

        $this->dropTable('banners_translations');

        $this->renameColumn('banners', 'ID', 'id');
        $this->renameColumn('banners', 'category', 'bannerTypeId');
        $this->renameColumn('banners', 'added', 'date');
        $this->renameColumn('banners', 'order', 'bannerOrder');
        $this->renameColumn('banners', 'dateFrom', 'dateStart');
        $this->renameColumn('banners', 'dateTo', 'dateEnd');

        $this->alterColumn('banners', 'type', Schema::TYPE_STRING);

        $this->addColumn('banners', 'banner_ru', Schema::TYPE_TEXT);
        $this->addColumn('banners', 'banner_uk', Schema::TYPE_TEXT);
        $this->addColumn('banners', 'banner_be', Schema::TYPE_TEXT);
        $this->addColumn('banners', 'link_ru', Schema::TYPE_STRING);
        $this->addColumn('banners', 'link_uk', Schema::TYPE_STRING);
        $this->addColumn('banners', 'link_be', Schema::TYPE_STRING);
        $this->addColumn('banners', 'state', Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0');
        $this->addColumn('banners', 'categoryCode', Schema::TYPE_STRING);
        $this->addColumn('banners', 'bg', Schema::TYPE_STRING);

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
