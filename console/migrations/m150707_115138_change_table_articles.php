<?php

use yii\db\Schema;
use yii\db\Migration;
use app\models\Articles;

class m150707_115138_change_table_articles extends Migration
{

    public function init(){
        $this->db = \Yii::$app->dbBlog;
        return parent::init();
    }

    public function up()
    {
        $this->execute("ALTER TABLE ".Articles::tableName()."
            DROP COLUMN `author`,
            DROP COLUMN `commentCount`,
            DROP COLUMN `status`,
            DROP COLUMN `type`,
            DROP COLUMN `publish`");
    }

    public function down()
    {
        echo "Table articles was reverted!\n";

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
