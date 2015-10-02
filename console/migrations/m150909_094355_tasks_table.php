<?php

use yii\db\Migration;
use yii\db\mysql\Schema;

class m150909_094355_tasks_table extends Migration
{
    public function up()
    {
        $this->createTable('tasks', [
            'id'            =>  Schema::TYPE_INTEGER.' AUTO_INCREMENT NOT NULL',
            'title'         =>  Schema::TYPE_STRING.' NOT NULL',
            'description'   =>  Schema::TYPE_TEXT.' DEFAULT NULL',
            'author'        =>  Schema::TYPE_INTEGER.' NOT NULL',
            'dateAdded'     =>  Schema::TYPE_DATE.' NOT NULL',
            'dateFrom'      =>  Schema::TYPE_DATETIME.' NOT NULL',
            'desiredDateTo' =>  Schema::TYPE_DATETIME.' NOT NULL',
            'dateTo'        =>  Schema::TYPE_DATETIME.' DEFAULT "0000-00-00 00:00:00"',
            'status'        =>  Schema::TYPE_INTEGER.' NOT NULL DEFAULT 0',
            'priority'      =>  Schema::TYPE_INTEGER.' NOT NULL DEFAULT 0',
            'PRIMARY KEY ( `id` )'
        ]);

        $this->createTable('tasks_users', [
            'id'    =>  Schema::TYPE_INTEGER.' AUTO_INCREMENT NOT NULL',
            'task_id'    =>  Schema::TYPE_INTEGER.' NULL',
            'user_id'    =>  Schema::TYPE_INTEGER.' NULL',
            'user_role'    =>  Schema::TYPE_INTEGER.' NULL DEFAULT \'0\'',
            'PRIMARY KEY ( `id` )',
        ]);
    }

    public function down()
    {
        $this->dropTable("tasks");
        $this->dropTable("tasks_users");

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
