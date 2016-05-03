<?php

use yii\db\Migration;

class m160427_144512_change_current_goodsgroups extends Migration
{
    public function up()
    {
        $this->execute("CREATE TABLE goodsgroups_copy LIKE goodsgroups; INSERT goodsgroups_copy SELECT * FROM goodsgroups;");
        $this->dropColumn('goodsgroups', 'Name');
        $this->dropColumn('goodsgroups', 'link');
        $this->dropColumn('goodsgroups', 'p_photo');
        $this->dropColumn('goodsgroups', 'enabled');
        $this->dropColumn('goodsgroups', 'title');
        $this->dropColumn('goodsgroups', 'titlenew');
        $this->dropColumn('goodsgroups', 'titleasc');
        $this->dropColumn('goodsgroups', 'titledesc');
        $this->dropColumn('goodsgroups', 'listorder');
        $this->dropColumn('goodsgroups', 'text2');
        $this->dropColumn('goodsgroups', 'descr');
        $this->dropColumn('goodsgroups', 'keyword');
        $this->dropColumn('goodsgroups', 'h1');
        $this->dropColumn('goodsgroups', 'h1asc');
        $this->dropColumn('goodsgroups', 'h1desc');
        $this->dropColumn('goodsgroups', 'h1new');
        $this->dropColumn('goodsgroups', 'catNameVinitelny');
        $this->dropColumn('goodsgroups', 'catNameVinitelny2');
    }

    public function down()
    {
        $this->dropTable('goodsgroups');
        $this->renameTable('goodsgroups_copy', 'goodsgroups');

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
