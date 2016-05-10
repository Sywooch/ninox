<?php

use common\models\CategoryTranslation;
use yii\db\Expression;
use yii\db\Migration;

class m160507_161144_update_moxie_manager_source_files extends Migration
{
    public function up()
    {
        CategoryTranslation::updateAll([
            'categoryDescription' => new Expression("REPLACE(`categoryDescription`, '/template/js/plugins/moxiemanager/data/files', '/img/category')")],
            ['like', 'categoryDescription', '/template/js/plugins/moxiemanager/data/files']);
    }

    public function down()
    {
        CategoryTranslation::updateAll([
            'categoryDescription' => new Expression("REPLACE(`categoryDescription`, '/img/category', '/template/js/plugins/moxiemanager/data/files')")],
            ['like', 'categoryDescription', '/img/category']);
        echo "m160507_161144_update_moxie_manager_source_files was successfully reverted.\n";
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
