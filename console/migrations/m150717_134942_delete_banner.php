<?php

use yii\db\Schema;
use yii\db\Migration;

class m150717_134942_delete_banner extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `banners` ADD COLUMN `deleted` INT(1) DEFAULT 0");
    }

    public function down()
    {
        echo "m150717_134942_delete_banner cannot be reverted.\n";

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
