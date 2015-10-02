<?php

use yii\db\Schema;
use yii\db\Migration;

class m150715_085741_user_default_route extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `siteusers` ADD COLUMN `default_route` TEXT DEFAULT NULL");
    }

    public function down()
    {
        echo "m150715_085741_user_default_route cannot be reverted.\n";

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
