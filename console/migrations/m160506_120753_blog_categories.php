<?php

use yii\db\Migration;

class m160506_120753_blog_categories extends Migration
{
    public function init(){
        $this->db = \Yii::$app->dbBlog;
        return parent::init();
    }
    
    public function up()
    {
        $this->addColumn(\common\models\BlogCategory::tableName(), 'parent', \yii\db\Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0');
        $this->addColumn(\common\models\BlogCategory::tableName(), 'taxonomy', \yii\db\Schema::TYPE_STRING." NOT NULL DEFAULT ''");

        $this->execute("UPDATE `".\common\models\BlogCategory::tableName()."` `a`, `wp_term_taxonomy` `b` SET `a`.`parent` = `b`.`parent`, `a`.`taxonomy` = `b`.`taxonomy` WHERE `a`.`term_id` = `b`.`term_id`");
    }

    public function down()
    {
        $this->dropColumn(\common\models\BlogCategory::tableName(), 'parent');
        $this->dropColumn(\common\models\BlogCategory::tableName(), 'taxonomy');

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
