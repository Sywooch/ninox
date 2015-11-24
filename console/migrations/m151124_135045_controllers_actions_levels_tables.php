<?php

use yii\db\Migration;

class m151124_135045_controllers_actions_levels_tables extends Migration
{
    public function up()
    {
        $this->createTable('yii_controllers', [
            'id'            =>  \yii\db\Schema::TYPE_INTEGER.' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'controller'    =>  \yii\db\Schema::TYPE_STRING.' NOT NULL',
        ]);

        $this->createTable('yii_actions', [
            'id'            =>  \yii\db\Schema::TYPE_INTEGER.' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'controllerID'  =>  \yii\db\Schema::TYPE_INTEGER.' UNSIGNED NOT NULL',
            'action'        =>  \yii\db\Schema::TYPE_STRING.' NOT NULL',
            'description'   =>  \yii\db\Schema::TYPE_STRING.' DEFAULT NULL',
        ]);

        $this->createTable('accessLevels', [
            'actionID'  =>  \yii\db\Schema::TYPE_INTEGER.' UNSIGNED NOT NULL PRIMARY KEY',
            'level'     =>  \yii\db\Schema::TYPE_INTEGER.' UNSIGNED NOT NULL',
            'description'   =>  \yii\db\Schema::TYPE_STRING.' DEFAULT NULL',
        ]);
    }

    public function down()
    {
        $this->dropTable('yii_controllers');
        $this->dropTable('yii_actions');
        $this->dropTable('accessLevels');

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
