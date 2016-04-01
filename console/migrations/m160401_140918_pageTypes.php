<?php

use yii\db\Migration;

class m160401_140918_pageTypes extends Migration
{
    public function up()
    {
        $this->dropTable('pagetype');
        $this->alterColumn(\common\models\Category::tableName(), 'pageType', \yii\db\Schema::TYPE_STRING);
        $this->renameColumn(\common\models\Category::tableName(), 'pageType', 'viewFile');
        $this->renameColumn(\common\models\Category::tableName(), 'menu_show', 'enabled');
        $this->addColumn(\common\models\Category::tableName(), 'viewOptions', \yii\db\Schema::TYPE_TEXT);

        \common\models\Category::updateAll(['viewFile' => 'category'], ['or', ['viewFile' => '0'], ['viewFile' => '13']]);
        \common\models\Category::updateAll(['viewFile' => 'o_nas'], ['or', ['link' => 'about'], ['link' => 'o-nas']]);
    }

    public function down()
    {
        $this->dropColumn(\common\models\Category::tableName(), 'viewOptions');
        $this->renameColumn(\common\models\Category::tableName(), 'viewFile', 'pageType');

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
