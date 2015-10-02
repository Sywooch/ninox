<?php

use yii\db\Schema;
use yii\db\Migration;

class m150728_130624_tasks_manager extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `siteusers` ADD COLUMN `avatar` TEXT DEFAULT NULL, ADD COLUMN `birthdate` DATE DEFAULT NULL, ADD COLUMN `tasksUser` INT(1) DEFAULT 0, ADD COLUMN `tasksRole` INT(1) DEFAULT 0, ADD COLUMN `workStatus` INT(1) DEFAULT 0");
    }

    public function down()
    {
        echo "m150728_130624_tasks_manager cannot be reverted.\n";

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
