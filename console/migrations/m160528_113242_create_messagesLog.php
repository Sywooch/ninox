<?php

use yii\db\Migration;

class m160528_113242_create_messagesLog extends Migration
{
    public function up()
    {
        $this->createTable('messageslog', [
            'id'        =>  $this->primaryKey(),
            'orderID'   =>  $this->integer()->unsigned()->notNull(),
            'messageID' =>  $this->integer()->unsigned()->notNull(),
            'changed'   =>  $this->timestamp()
        ]);

        $this->createIndex('orderID', 'messageslog', 'orderID');
        $this->createIndex('messageID', 'messageslog', 'messageID');
    }

    public function down()
    {
        $this->dropTable('messageslog');
    }
}
