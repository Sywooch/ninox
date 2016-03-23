<?php

use yii\db\Schema;
use yii\db\Migration;

class m150717_134942_delete_banner extends Migration
{
    public function up()
    {
        $this->addColumn('banners', 'deleted', Schema::TYPE_SMALLINT.' UNSIGNED NOT NULL DEFAULT 0');
    }

    public function down()
    {
        $this->dropColumn('banners', 'deleted');
        echo "m150717_134942_delete_banner was successfully reverted.\n";
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
