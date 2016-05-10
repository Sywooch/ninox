<?php

use common\models\BlogArticle;
use yii\db\Migration;

class m160505_161439_migrate_nahui_from_wordpress_i_pobystree extends Migration
{
    public function init(){
        $this->db = \Yii::$app->dbBlog;
        return parent::init();
    }

    public function up()
    {
        $this->addColumn(\common\models\BlogArticle::tableName(), 'category', \yii\db\Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0');

        $this->execute("UPDATE ".BlogArticle::tableName()." a, wp_term_relationships b SET `a`.`category` = `b`.`term_taxonomy_id` WHERE `a`.`id` = `b`.`object_id`");

        $this->dropTable('wp_term_relationships');
    }

    public function down()
    {
        echo "m160505_161439_migrate_nahui_from_wordpress_i_pobystree cannot be reverted.\n";

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
