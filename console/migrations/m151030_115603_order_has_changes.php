<?php

use yii\db\Migration;

class m151030_115603_order_has_changes extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\History::tableName(), 'hasChanges', \yii\db\mysql\Schema::TYPE_INTEGER.' NOT NULL DEFAULT 0');
    }

    public function down()
    {

        $this->dropColumn(\common\models\History::tableName(), 'hasChanges');
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
