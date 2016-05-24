<?php

use yii\db\Migration;

class m160524_125243_url_banner4 extends Migration
{
    public function up()
    {
        $banner = \common\models\BannerTranslation::findOne(['ID' => 489]);
        if($banner){
            $banner->value = '{"goodID":33541,"image":"watch.png"}';
            $banner->save(false);
        }
    }

    public function down()
    {
        $banner = \common\models\BannerTranslation::findOne(['ID' => 489]);
        if($banner){
            $banner->value = '{"goodID":12816,"image":"watch.png"}';
            $banner->save(false);
        }

        echo "m160524_125243_url_banner4 cannot be reverted.\n";

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
