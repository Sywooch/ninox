<?php

use yii\db\Migration;

class m151124_113528_feedbacks_fix extends Migration
{
    public function up()
    {
        $this->addColumn('reviews', 'deleted', \yii\db\Schema::TYPE_SMALLINT.' NOT NULL DEFAULT 0');
        $this->addColumn('reviews', 'customerID', \yii\db\Schema::TYPE_INTEGER.' DEFAULT NULL');
        $this->addColumn('reviews', 'customerPhoto', \yii\db\Schema::TYPE_STRING.' DEFAULT NULL');
        $this->addColumn('questions', 'deleted', \yii\db\Schema::TYPE_SMALLINT.' NOT NULL DEFAULT 0');
        $this->addColumn('callback', 'deleted', \yii\db\Schema::TYPE_SMALLINT.' NOT NULL DEFAULT 0');
        $this->renameColumn('callback', 'nomer_telefona', 'phone');
        $this->renameColumn('callback', 'vopros', 'question');
        $this->renameColumn('callback', 'date_resive', 'received');
        $this->renameColumn('callback', 'client_name', 'customerName');
        $this->renameColumn('problems', 'id_problem', 'id');
        $this->renameColumn('problems', 'nomer_zakaza', 'orderNumber');
        $this->renameColumn('problems', 'nomer_telefona', 'phone');
        $this->renameColumn('problems', 'problema', 'text');
        $this->renameColumn('problems', 'date_resive', 'received');
        $this->renameColumn('problems', 'read_problem', 'read');
    }

    public function down()
    {
        $this->dropColumn('reviews', 'deleted');
        $this->dropColumn('reviews', 'customerID');
        $this->dropColumn('reviews', 'customerPhoto');
        $this->dropColumn('questions', 'deleted');
        $this->dropColumn('callback', 'deleted');
        $this->renameColumn('callback', 'phone', 'nomer_telefona');
        $this->renameColumn('callback', 'question', 'vopros');
        $this->renameColumn('callback', 'received', 'date_resive');
        $this->renameColumn('callback', 'customerName', 'client_name');
        $this->renameColumn('problems', 'id', 'id_problem');
        $this->renameColumn('problems', 'orderNumber', 'nomer_zakaza');
        $this->renameColumn('problems', 'phone', 'nomer_telefona');
        $this->renameColumn('problems', 'text', 'problema');
        $this->renameColumn('problems', 'received', 'date_resive');
        $this->renameColumn('problems', 'read', 'read_problem');

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
