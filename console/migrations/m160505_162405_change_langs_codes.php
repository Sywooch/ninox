<?php

use common\models\BannerTranslation;
use common\models\CategoryTranslation;
use common\models\GoodTranslation;
use yii\db\Migration;

class m160505_162405_change_langs_codes extends Migration
{
    public function up()
    {
        CategoryTranslation::updateAll(['language' => 'ru-RU'], ['language' => 'ru_RU']);
        CategoryTranslation::updateAll(['language' => 'uk-UA'], ['language' => 'uk_UA']);
        CategoryTranslation::updateAll(['language' => 'be-BY'], ['language' => 'be_BY']);
        GoodTranslation::updateAll(['language' => 'ru-RU'], ['language' => 'ru_RU']);
        GoodTranslation::updateAll(['language' => 'uk-UA'], ['language' => 'uk_UA']);
        GoodTranslation::updateAll(['language' => 'be-BY'], ['language' => 'be_BY']);
        BannerTranslation::updateAll(['language' => 'ru-RU'], ['language' => 'ru_RU']);
        BannerTranslation::updateAll(['language' => 'uk-UA'], ['language' => 'uk_UA']);
        BannerTranslation::updateAll(['language' => 'be-BY'], ['language' => 'be_BY']);
    }

    public function down()
    {
        CategoryTranslation::updateAll(['language' => 'ru_RU'], ['language' => 'ru-RU']);
        CategoryTranslation::updateAll(['language' => 'uk_UA'], ['language' => 'uk-UA']);
        CategoryTranslation::updateAll(['language' => 'be_BY'], ['language' => 'be-BY']);
        GoodTranslation::updateAll(['language' => 'ru_RU'], ['language' => 'ru-RU']);
        GoodTranslation::updateAll(['language' => 'uk_UA'], ['language' => 'uk-UA']);
        GoodTranslation::updateAll(['language' => 'be_BY'], ['language' => 'be-BY']);
        BannerTranslation::updateAll(['language' => 'ru_RU'], ['language' => 'ru-RU']);
        BannerTranslation::updateAll(['language' => 'uk_UA'], ['language' => 'uk-UA']);
        BannerTranslation::updateAll(['language' => 'be_BY'], ['language' => 'be-BY']);
        echo "m160505_162405_change_langs_codes was successfully reverted.\n";
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
