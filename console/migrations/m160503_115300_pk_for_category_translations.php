<?php

use yii\db\Migration;

class m160503_115300_pk_for_category_translations extends Migration
{
    public function up()
    {
        $this->addPrimaryKey('pk_id_language', 'category_translations', ['ID', 'language']);
        $this->alterColumn('category_translations', 'language', \yii\db\Schema::TYPE_STRING.'(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'ru_RU\'');
    }

    public function down()
    {
        echo "m160503_113333_pk_for_category_translations cannot be reverted.\n";
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
