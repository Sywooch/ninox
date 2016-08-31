<?php

use yii\db\Migration;
use yii\db\Schema;

class m160831_124239_date_last_call_in_history extends Migration
{
    public function up()
    {
        //$this->addColumn('history', 'callbackDate', Schema::TYPE_DATETIME." NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `callback`");
        common\models\History::updateAll(['callbackDate' => new \yii\db\Expression('`confirmedDate`')], "`confirmedDate` IS NOT NULL AND `confirmedDate` != '0000-00-00 00:00:00'");
    }

    public function down()
    {
        $this->dropColumn('history', 'callbackDate');
        echo "m160831_124239_date_last_call_in_history was successfully reverted.\n";
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
