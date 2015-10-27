<?php

use yii\db\Migration;

class m151027_122016_nakladna_send_date extends Migration
{
    public function up()
    {
        $this->addColumn(\common\models\History::tableName(), 'nakladnaSendDate', \yii\db\mysql\Schema::TYPE_DATETIME.' DEFAULT NULL');
    }

    public function down()
    {

        $this->dropColumn(\common\models\History::tableName(), 'nakladnaSendDate');
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
