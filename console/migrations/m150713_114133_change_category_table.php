<?php

use yii\db\Schema;
use yii\db\Migration;

class m150713_114133_change_category_table extends Migration
{

    public static $DB = 'blog_new';

    public function init(){
        $this->db = \Yii::$app->dbBlog;
        return parent::init();
    }

    public function safeUp()
    {
        $this->execute("RENAME TABLE `wp_terms` TO `category`");
        $this->execute("RENAME TABLE `wp_term_relationships` TO `term_relationships`");

        $this->execute("ALTER TABLE `category`
            DROP COLUMN `term_group`,
            CHANGE COLUMN `term_id` `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            ADD `parent` int(10) UNSIGNED NOT NULL AFTER `id`");

        $this->execute("CREATE TABLE `tag` LIKE `category`");
        $this->execute("INSERT `tag` SELECT * FROM `category`");

        $this->execute("ALTER TABLE `tag` DROP COLUMN `parent`");

        //удаляем в category тэги, а в tag категории

        $this->execute("DELETE FROM `category` WHERE `id` IN (
              SELECT `term_id` FROM `wp_term_taxonomy` WHERE `taxonomy` = 'post_tag'
            )
        ");

        $this->execute("DELETE
            FROM `tag`
            WHERE `id` IN (
              SELECT `term_id` FROM `wp_term_taxonomy` WHERE `taxonomy` = 'category'
            )
        ");


        $this->execute("ALTER TABLE `term_relationships`
            DROP COLUMN `term_order`,
            CHANGE COLUMN `object_id` `article_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
            CHANGE COLUMN `term_taxonomy_id` `cat_id` int(10) UNSIGNED NOT NULL DEFAULT 0");

        $this->execute("CREATE TABLE `tag_relationships` LIKE `term_relationships`");
        $this->execute("INSERT `tag_relationships` SELECT * FROM `term_relationships`");

        $this->execute("RENAME TABLE `term_relationships` TO `category_relationships`");

        $this->execute("ALTER TABLE `tag_relationships`
            CHANGE COLUMN `cat_id` `tag_id` int(10) UNSIGNED NOT NULL DEFAULT 0");

        $this->execute("DELETE
            FROM `category_relationships`
            WHERE `cat_id` IN (
              SELECT `id` FROM `tag`
            )
            ");

        $this->execute("DELETE
            FROM `tag_relationships`
            WHERE `tag_id` IN (
              SELECT `id` FROM `category`
            )
            ");

        //расставляем parent'ы для категорий
        $this->execute("UPDATE `category`
            SET `parent` =
                 (
                   SELECT `parent`
                   FROM `wp_term_taxonomy`
                   WHERE `wp_term_taxonomy`.`term_id` = `category`.`id`
                   LIMIT 1
                 )
        ");

        $this->execute("DROP TABLE `wp_term_taxonomy`");
    }
    
    public function safeDown()
    {
        echo "m150713_114133_change_category_table cannot be reverted.\n";

        return true;
    }
}
