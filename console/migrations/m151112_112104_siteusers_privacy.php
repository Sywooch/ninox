<?php

use yii\db\Migration;

class m151112_112104_siteusers_privacy extends Migration
{
    public function up()
    {
        $this->createTable('siteusersPrivacy', [
            'userID'    =>  \yii\db\mysql\Schema::TYPE_INTEGER.' NOT NULL',
            'controller'=>  \yii\db\mysql\Schema::TYPE_STRING.' NOT NULL',
            'action'    =>  \yii\db\mysql\Schema::TYPE_STRING.' NOT NULL',
            'level'     =>  \yii\db\mysql\Schema::TYPE_INTEGER.' NOT NULL DEFAULT 0'
        ]);
    }

    public function down()
    {
        $this->dropTable('siteusersPrivacy');

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
