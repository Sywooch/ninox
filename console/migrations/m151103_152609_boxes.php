<?php

use yii\db\Migration;
use yii\db\Schema;

class m151103_152609_boxes extends Migration
{
    public function up()
    {
        $this->createTable('boxes', [
            'id'                =>  Schema::TYPE_INTEGER.' AUTO_INCREMENT NOT NULL',
            'volumeWeight'      =>  Schema::TYPE_INTEGER.' DEFAULT NULL',
            'volumeGeneral'     =>  Schema::TYPE_FLOAT.' (24, 4) DEFAULT NULL',
            'volumetricVolume'  =>  Schema::TYPE_FLOAT.' (24, 4) DEFAULT NULL',
            'volumetricWidth'   =>  Schema::TYPE_INTEGER.' DEFAULT NULL',
            'volumetricLength'  =>  Schema::TYPE_INTEGER.' DEFAULT NULL',
            'volumetricHeight'  =>  Schema::TYPE_INTEGER.' DEFAULT NULL',
            'PRIMARY KEY ( `id` )'
        ]);
    }

    public function down()
    {
        $this->dropTable('boxes');

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
