<?php

use yii\db\Schema;
use yii\db\Migration;

class m150709_102846_feedback_table_changes extends Migration
{
    public function up()
    {

    }

    public function down()
    {
        echo "m150709_102846_feedback_table_changes cannot be reverted.\n";
        $this->execute("ALTER TABLE `reviews` ADD COLUMN `deleted` INT(1) NOT NULL DEFAULT 0");

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
