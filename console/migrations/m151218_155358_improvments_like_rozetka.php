<?php

use yii\db\Migration;

class m151218_155358_improvments_like_rozetka extends Migration
{
    public function up()
    {
        $this->addColumn('partners', 'giveFeedbackClosed', \yii\db\Schema::TYPE_DATETIME);
    }

    public function down()
    {
        return $this->dropColumn('partners', 'giveFeedbackClosed');
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
