<?php

use yii\db\Migration;

class m160517_093913_create_comments_table extends Migration
{
    public function up()
    {
        $this->createTable('comments', [
            'id'        =>  $this->primaryKey(),
            'comment'   =>  $this->text(),
            'model'     =>  $this->string()->notNull(),
            'modelID'   =>  $this->string()->notNull(),
            'stamp'     =>  $this->timestamp()->notNull(),
            'userID'    =>  $this->integer()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('id', 'comments', 'id');
        $this->createIndex('model', 'comments', 'model');
        $this->createIndex('modelID', 'comments', 'modelID');
        $this->createIndex('userID', 'comments', 'userID');
    }

    public function down()
    {
        $this->dropTable('comments');
    }
}
