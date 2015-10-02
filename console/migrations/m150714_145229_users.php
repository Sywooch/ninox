<?php

use yii\db\Schema;
use yii\db\Migration;

class m150714_145229_users extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `siteusers` CHANGE COLUMN `login` `username` VARCHAR(60) NOT NULL, CHANGE `pass` `password` TEXT DEFAULT NULL, ADD COLUMN `auth_key` TEXT DEFAULT NULL");
    }

    public function down()
    {
        echo "m150714_145229_users cannot be reverted.\n";

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
