<?php

use yii\db\Migration;

class m160630_160211_add_cost_creator extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\Cost::tableName(), 'creator', $this->integer()->unsigned()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn(\common\models\Cost::tableName(), 'creator');
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
