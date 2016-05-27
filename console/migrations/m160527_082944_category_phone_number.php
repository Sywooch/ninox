<?php

use yii\db\Migration;

class m160527_082944_category_phone_number extends Migration
{
    public function up()
    {
        $this->addColumn('category_translations', 'phoneNumber', \yii\db\Schema::TYPE_STRING."(25) NOT NULL DEFAULT ''");
        \common\models\CategoryTranslation::updateAll(['phoneNumber' => '(067) 325 81 89'], ['ID' => 331, 'language' => ['ru-RU', 'uk-UA']]);
    }

    public function down()
    {
        $this->dropColumn('category_translations', 'phoneNumber');

        echo "m160526_105839_category_phone_number was successfully reverted.\n";

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
