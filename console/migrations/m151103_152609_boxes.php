<?php

use yii\db\Migration;

class m151103_152609_boxes extends Migration
{
    public function up()
    {
        $this->createTable('boxes', [
            'id'                =>  \yii\db\mysql\Schema::TYPE_INTEGER.' AUTO_INCREMENT NOT NULL',
            'volumeWeight'      =>  \yii\db\mysql\Schema::TYPE_INTEGER.' DEFAULT NULL',
            'volumeGeneral'     =>  \yii\db\mysql\Schema::TYPE_FLOAT.' (24, 4) DEFAULT NULL',
            'volumetricVolume'  =>  \yii\db\mysql\Schema::TYPE_FLOAT.' (24, 4) DEFAULT NULL',
            'volumetricWidth'   =>  \yii\db\mysql\Schema::TYPE_INTEGER.' DEFAULT NULL',
            'volumetricLength'  =>  \yii\db\mysql\Schema::TYPE_INTEGER.' DEFAULT NULL',
            'volumetricHeight'  =>  \yii\db\mysql\Schema::TYPE_INTEGER.' DEFAULT NULL',
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
