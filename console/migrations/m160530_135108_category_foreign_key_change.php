<?php

use yii\db\Migration;

class m160530_135108_category_foreign_key_change extends Migration
{
    public function up()
    {
        $this->dropForeignKey('fk_category_category_translations', \common\models\Category::tableName());
        $this->addForeignKey('fk_category_category_translations', \common\models\CategoryTranslation::tableName(), 'ID', \common\models\Category::tableName(), 'ID');
    }

    public function down()
    {
        $this->dropForeignKey('fk_category_category_translations', \common\models\CategoryTranslation::tableName());
        $this->addForeignKey('fk_category_category_translations', \common\models\Category::tableName(), 'ID', \common\models\CategoryTranslation::tableName(), 'ID');

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
