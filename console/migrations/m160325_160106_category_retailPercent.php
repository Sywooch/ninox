<?php

use yii\db\Migration;

class m160325_160106_category_retailPercent extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\Category::tableName(), 'retailPercent', \yii\db\Schema::TYPE_FLOAT.' UNSIGNED NOT NULL DEFAULT 20');
    }

    public function down()
    {
        return $this->dropColumn(\common\models\Category::tableName(), 'retailPercent');
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
